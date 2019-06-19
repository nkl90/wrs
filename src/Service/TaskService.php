<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class TaskService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function all() : array
    {
        return $this->entityManager
        ->getRepository(Task::class)
        ->findAll();
    }
    
    public function oneById(int $id) : Task
    {
        return $this->entityManager
        ->getRepository(Task::class)
        ->find($id);
    }
    
    public function create(Task $task) : Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        
        return $task;
    }
    
    public function allTasksInAllTeamWhereUserParticipate(User $user) : array
    {
        $tasks = [];

        $teams = $user->getTeams();
        
        foreach ($teams as $team) {
            if (!empty($team->getTasks())) {
                foreach ($team->getTasks() as $task) {
                    $tasks[] = $task;
                }
            }
        }
        
        return $tasks;
    }
    
    public function allProjectTaskByUser(User $user) : array
    {
        $tasks = [];
        $projects = $user->getProjects();
        
        foreach ($projects as $project) {
            if (!empty($project->getTasks())) {
                foreach ($project->getTasks() as $task) {
                    $tasks[] = $task;
                }
            }
        }
        
        return $tasks;
    }
    
    public function allProjectTaskExceptUserExecutorByUser(User $user) : array
    {
        $res = [];
        $tasks = $this->allProjectTaskByUser($user);
        
        foreach ($tasks as $task) {
            if ($task->getExecutor()->getId() !== $user->getId()) {
                $res[] = $task;
            }
        }
        
        return $res;
    }


    public function userCreatedTasks(User $user) : Collection
    {
        return $this
            ->entityManager
            ->getRepository(Task::class)
            ->userCreatedTasks($user)
        ;
    }
}

