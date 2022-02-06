<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Tags;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;

use App\Repository\AuthorRepository;
use App\Repository\TagsRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class KennyController extends AbstractController
{

    /**
     * @Route("/", name="kenny")
     */
    public function index(ArticleRepository $articleRepository,TagsRepository $tagsRepository, PaginatorInterface $paginator, Request $request)
    {
        $articles = $paginator->paginate($articleRepository->findAll(),
        $request->query->getInt('page',1),
        3
        );
        $tags = $tagsRepository->findAll();
        $rarticles = $articleRepository->desc();
        return $this->render('kenny/index.html.twig', [
            'articles' => $articles,
            'tags' => $tags,
            'rarticle' => $rarticles
        ]);
    }



    /**
     * @Route("article/new", name="article_new")
     */
    public function new(Request $request ,AuthorRepository $authorRepository ,FlashyNotifier $flashyNotifier){
        $article = new Article();
        $author = new Author();
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            $article->setCreatedAt(new \DateTime());

           // $article->setImage("https://picsum.photos/seed/picsum/300/150");

            $email = $article->getAuthor()->getEmail();
            $name = $article->getAuthor()->getName();
            $tag = $article->getTag()->getName();

            if($tag == "Databases"){
                $article->setImage("https://picsum.photos/id/370/300/150");
            }elseif ($tag == "Computer security and cryptography"){
                $article->setImage("https://picsum.photos/id/9/300/150");
            }elseif ($tag == "Computer networks"){
                $article->setImage("https://picsum.photos/id/1/300/150");
            }elseif ($tag == "Data structures and algorithms"){
                $article->setImage("https://picsum.photos/id/445/300/150");
            }else{
                $article->setImage("https://picsum.photos/id/366/300/150");
            }

            $authors = $authorRepository->findOneBy(['email'=>$email]);
            if($authors != null){
                $article->setAuthor($authors);
            }else if($authors == null){
                $author->setName($name);
                $author->setEmail($email);
                $author->setLikes(0);
                $article->setAuthor($author);
                $entityManager->persist($author);
            }

            $entityManager->persist($article);
            $entityManager->flush();
            $flashyNotifier->success('Article Published Successfully!', 'http://your-awesome-link.com');
            return $this->redirectToRoute("article_show",[ 'id'=>$article->getId() ]);
            //Ebai
            //ajax
        }

        return $this->render('kenny/new.html.twig',[
            'form'=>$form->createView()

        ]);
    }


    /**
     * @Route("influencers_ranking ", name="rank")
     */
    public function rank(AuthorRepository $authorRepository){
        $author = new Author();

        $author = $authorRepository->desc();

        return $this->render('kenny/rank.html.twig',[
            'authors' => $author
        ]);
    }


    /**
     * @Route("kenny/About", name="about")
     */
    public function about(){
        return $this->render('kenny/about.html.twig');
    }

    /**
     * @Route("article/{id}/edit", name="article_edit")
     */

    public function edit(Request $request,Article $article,FlashyNotifier $flashyNotifier): \Symfony\Component\HttpFoundation\Response{
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        $likes = $article->getAuthor()->getLikes();
        if($likes == 0) {

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();

                $flashyNotifier->success('Article Edited Successfully!', 'http://your-awesome-link.com');
                return $this->redirectToRoute("article_show", ['id' => $article->getId()]);

            } else {
                $flashyNotifier->warning('This Email is Already Exist', 'http://your-awesome-link.com');
            }
        }else{
            $flashyNotifier->error('This Email Cannot be Edited Any More', 'http://your-awesome-link.com');
            return $this->redirectToRoute("article_show", ['id' => $article->getId()]);
        }

        return $this->render('kenny/edit.html.twig',[
            'edit_form'=>$form->createView()
        ]);
    }
     /**
      * @Route("article/{id}/like", name="article_like")
      */
     public function like(Request $request,ArticleRepository $articleRepository,AuthorRepository $authorRepository ,FlashyNotifier $flashyNotifier)
    {
        $response = new JsonResponse();

        $id = $request->attributes->get('id');

        $cookie = $request->cookies->get('article_'.$id);

        if($cookie){
            $flashyNotifier->info('You Already Liked This Article !', 'http://your-awesome-link.com');

            return $this->redirectToRoute("kenny");

        }
        $response->headers->setCookie(Cookie::create('article_'.$id, 'bar'));

         //$article = new Article();
        $author = new Author();
        $entityManager  = $this->getDoctrine()->getManager();
        $article = $articleRepository->findOneBy([
                'id' => $id,
        ]);
        $author = $article->getAuthor();

        $likes = $author->getLikes();
        $author->setLikes($likes+1);
        $entityManager->persist($author);
        $entityManager->flush();
        $flashyNotifier->success('You Liked This Article !', 'http://your-awesome-link.com');

        return $this->redirectToRoute("kenny");

    }

    /**
     * @Route("tags/{id}", name="category_articles")
     */
    public function tags(Request $request,ArticleRepository $articleRepository,TagsRepository $tagsRepository,Article $articles ,Tags $tags){
        $id_tag = $request->attributes->get('id');
        $tags = $tagsRepository->findAll();
        $rarticles = $articleRepository->desc();
        $articles = $this->getDoctrine()
        ->getRepository(Article::class)->findAllWhereId($id_tag);
        //dd($articles);
        return $this->render('kenny/tags.html.twig',[
            'articles'=>$articles,
            'tags'=>$tags,
            'rarticles'=>$rarticles
        ]);
    }

    /**
     * @Route("article/{id}", name="article_show")
     */
    public function show(Request $request,ArticleRepository $articleRepository,TagsRepository $tagsRepository,Article $article ,Tags $tags){
        $id_tag = $request->attributes->get('id');
        $rarticles = $articleRepository->desc();
        $tags = $tagsRepository->findAll();
        return $this->render('kenny/show.html.twig',[
            'article' => $article,
            'rarticles'=>$rarticles,
            'tags'=>$tags
        ]);

    }



}
