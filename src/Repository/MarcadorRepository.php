<?php

namespace App\Repository;

use App\Entity\Marcador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Marcador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marcador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marcador[]    findAll()
 * @method Marcador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarcadorRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Marcador::class);
    }

    public function buscarPorNombreCategoria($nombreCategoria, $pagina = 1, $elementosPorPagina = 5) {
        $query = $this->createQueryBuilder('m')
                ->innerJoin('m.categoria', 'c')
                ->where('c.nombre = :nombreCategoria')
                ->setParameter('nombreCategoria', $nombreCategoria)
                ->orderBy('m.creado', 'DESC')
                ->getQuery();

        return $this->paginacion($query, $pagina, $elementosPorPagina);
    }

    public function buscarTodos($pagina = 1, $elementosPorPagina = 5) {
        $query = $this->createQueryBuilder('m')
                ->orderBy('m.creado', 'DESC')
                ->addOrderBy('m.nombre', 'ASC')
                ->getQuery();

        return $this->paginacion($query, $pagina, $elementosPorPagina);
    }

    public function buscarPorNombre($nombre, $pagina = 1, $elementosPorPagina = 5) {
        $query = $this->createQueryBuilder('m')
                ->where('m.nombre LIKE :nombre')
                ->setParameter('nombre', "%$nombre%")
                ->orderBy('m.creado', 'DESC')
                ->getQuery();

        return $this->paginacion($query, $pagina, $elementosPorPagina);
    }
    
    public function buscarPorFavoritos($pagina = 1, $elementosPorPagina = 5) {
        $query = $this->createQueryBuilder('m')
                ->where('m.favorito = true')
                ->orderBy('m.creado', 'DESC')
                ->getQuery();

        return $this->paginacion($query, $pagina, $elementosPorPagina);
    }

    public function paginacion($dql, $pagina = 1, $elementosPorPagina = 5) {
        $paginador = new Paginator($dql);
        $paginador->getQuery()
                ->setFirstResult($elementosPorPagina * ($pagina - 1))
                ->setMaxResults($elementosPorPagina);

        return $paginador;
    }

    // /**
    //  * @return Marcador[] Returns an array of Marcador objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('m')
      ->andWhere('m.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('m.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Marcador
      {
      return $this->createQueryBuilder('m')
      ->andWhere('m.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
