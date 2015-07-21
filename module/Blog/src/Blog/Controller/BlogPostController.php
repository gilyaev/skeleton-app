<?php

namespace Blog\Controller;

use Blog\Entity\BlogPost;
use Blog\Form\BlogPostForm;
use Blog\Form\BlogPostInputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogPostController extends AbstractActionController
{
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        if ($this->isAllowed('controller/Blog\Controller\BlogPost:edit')) {
            $state = [];
        }
        else {
            $state = ['state' => 1];
        }
        $posts = $em
            ->getRepository('\Blog\Entity\BlogPost')
            ->findBy($state, array('created' => 'DESC'));
        $view = new ViewModel(array(
            'posts' => $posts,
        ));

        return $view;
    }

    public function addAction()
    {
        $form = new BlogPostForm();
        $form->get('submit')->setValue('Add');

        $inputFilter = new BlogPostInputFilter();
        $form->setInputFilter($inputFilter);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $blogpost = new BlogPost();

                $blogpost->exchangeArray($form->getData());
                $blogpost->setCreated(time());
                $blogpost->setUserId(0);

                $em->persist($blogpost);
                $em->flush();

                $message = 'Blogpost succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                return $this->redirect()->toRoute('blog');
            }
            else {
                $message = 'Error while saving blogpost';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return array('form' => $form);
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage("Blogpost {$id} not found");
            return $this->redirect()->toRoute('blog');
        }

        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $post = $em
            ->getRepository('\Blog\Entity\BlogPost')
            ->findOneBy(array('id' => $id));

        if (!$post) {
            $this->flashMessenger()->addErrorMessage(sprintf('Blogpost with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('blog');
        }

        $view = new ViewModel(array(
            'post' => $post->getArrayCopy(),
        ));

        return $view;
    }

    public function editAction()
    {
        $form = new BlogPostForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                $this->flashMessenger()->addErrorMessage('Blogpost id doesn\'t set');
                return $this->redirect()->toRoute('blog');
            }
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $post = $em
                ->getRepository('\Blog\Entity\BlogPost')
                ->findOneBy(array('id' => $id));
            if (!$post) {
                $this->flashMessenger()->addErrorMessage(sprintf('Blogpost with id %s doesn\'t exists', $id));
                return $this->redirect()->toRoute('blog');
            }

            $form->bind($post);

            return array('form' => $form);
        }
        else {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $data = $form->getData();
                $id = $data['id'];
                try {
                    $blogpost = $em->find('\Blog\Entity\BlogPost', $id);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('blog', array(
                        'action' => 'index'
                    ));
                }
                $blogpost->exchangeArray($form->getData());
                $em->persist($blogpost);
                $em->flush();
                $message = 'Blogpost succesfully saved!';
                $this->flashMessenger()->addMessage($message);
                
                return $this->redirect()->toRoute('blog');
            }
            else {
                $message = 'Error while saving blogpost';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return array('form' => $form);

    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Blogpost id doesn\'t set');
            return $this->redirect()->toRoute('blog');
        }
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        if ($this->params()->fromRoute('id') !== null) {
            $id = (int) $this->params()->fromRoute('id');
            try {
                $blogpost = $em->find('Blog\Entity\BlogPost', $id);
                $em->remove($blogpost);
                $em->flush();
            }
            catch (\Exception $ex) {
                $this->flashMessenger()->addErrorMessage('Error while deleting data');
                return $this->redirect()->toRoute('blog', array(
                    'action' => 'index'
                ));
            }
            $this->flashMessenger()->addMessage(sprintf('Blogpost %d was succesfully deleted', $id));
        }
        return $this->redirect()->toRoute('blog');
    }
} 