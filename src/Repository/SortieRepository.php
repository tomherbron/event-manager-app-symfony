<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Form\SortieFilterType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByFilters(FormInterface $filterForm)
    {

        $keywords = $filterForm->get('keywords')->getData();
        $campus = $filterForm->get('campus')->getData();
        $estOrganisateur = $filterForm->get('estOrganisateur')->getData();
        $estInscrit = $filterForm->get('estInscrit')->getData();
        $pasInscrit = $filterForm->get('pasInscrit')->getData();
        $sortiesPassees = $filterForm->get('sortiesPassees')->getData();

        $qb = $this->createQueryBuilder('s');


        if ($keywords) {
            $qb->where($qb->expr()->like('s.nom', ':keywords'))
                ->setParameter('keywords', '%' . $keywords . '%');
        }


        if ($campus) {
            $qb->andWhere('s.campus =:campus')
                ->setParameter('campus', $campus);
        }

        if ($estOrganisateur) {
            $currentUser = $this->security->getUser();
            $qb->andWhere('s.organisateur =:currentUser')
                ->setParameter('currentUser', $currentUser);
        }

        if ($estInscrit) {
            $currentUser = $this->security->getUser();
            $qb->select('s')
                ->from(Sortie::class, 'sc')
                ->leftJoin('s.organisateur', 'o')
                ->leftJoin('s.utilisateurs', 'i')
                ->andWhere($qb->expr()->eq('i', ':currentUser'))
                ->setParameter('currentUser', $currentUser);

        }
        if ($pasInscrit) {
            $currentUser = $this->security->getUser();
            $subQueryBuilder = $this->createQueryBuilder('sub')
                ->select('sub.id')
                ->leftJoin('sub.utilisateurs', 'subUser')
                ->andWhere('subUser = :currentUser')
                ->setParameter('currentUser', $currentUser);

            $qb->select('s')
                ->from(Sortie::class, 'sc')
                ->leftJoin('s.organisateur', 'o')
                ->andWhere($qb->expr()->notIn('s.id', $subQueryBuilder->getDQL()))
                ->setParameter('currentUser', $currentUser);
        }

        if ($sortiesPassees){
            $qb->select('s')
                ->leftJoin('s.etat', 'etat')
                ->andWhere('etat.id =:etatPasse')
                ->setParameter('etatPasse', 5);
        }

        return $qb->getQuery()->getResult();
    }
}
