<?php
namespace App\Controller;

use App\Entity\Categ;
use App\Entity\User;
use App\Repository\CategRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class AdminCategs{
    public function __invoke()
    {
        startSession();
        $lang = getTextLangue($_SESSION['locale']);

        if (isset($_SESSION["user"])){
            $em = EntityManager::getInstance();

            /** @var UserRepository$userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

            /** @var CategRepository$categRepository */
            $categRepository = $em->getRepository(Categ::class);

            if ($user->isAdmin()){
                if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                    if(!$_POST){
                        $categs = $categRepository->findBy(array(), array("categName" => "ASC"));

                        return new Response('admin/adminCategs.html.twig', ['lang' => $lang, 'categs' => $categs, 'user' => isUser()]);
                    } else {
                        if($_POST["method"] === "add"){
                            $categOfName = $categRepository->findOneBy(array("categName" => $_POST["name"]));
                            if($categOfName === null){
                                $categs = $categRepository->findBy(array(), array("categId" => "DESC"));

                                $categ = new Categ();
                                $categ->setCategId($categs[0]->getCategId() + 1);
                                $categ->setCategName($_POST["name"]);
                                $categ->setCategDesc($_POST["resume"]);

                                $em->persist($categ);
                                $em->flush();

                                $data = [];
                                $data["msg"] = $lang["ADMINCATEG"]["ADDMSG"];
                                $data["name"] = $categ->getCategName();
                                $data["categId"] = $categ->getCategId();
                                $data["resume"] = $categ->getCategDesc();
                                $data["update"] = $lang["ADMINCATEG"]["UPDATE"];
                                echo(json_encode($data));
                            } else {
                                $data = [];
                                $data["response"] = "impossible";
                                $data["error"] = $lang["ADMINCATEG"]["ERRORNAME"];
                                echo json_encode($data);
                            }
                        }
                        elseif ($_POST["method"] === "update"){
                            $categOfName = $categRepository->findOneBy(array("categName" => $_POST["name"]));
                            if($categOfName === null || strval($categOfName->getCategId()) === $_POST["categId"]){
                                $categ = $categRepository->findOneBy(['categId' => $_POST["categId"]]);

                                $categ->setCategName($_POST["name"]);
                                $categ->setCategDesc($_POST["resume"]);

                                $em->persist($categ);
                                $em->flush();

                                $data = [];
                                $data["msg"] = $lang["ADMINCATEG"]["UPDATEMSG"];
                                $data["name"] = $categ->getCategName();
                                $data["categId"] = $categ->getCategId();
                                $data["resume"] = $categ->getCategDesc();
                                $data["update"] = $lang["ADMINCHAPTER"]["UPDATE"];
                                echo(json_encode($data));
                            } else {
                                $data = [];
                                $data["response"] = "impossible";
                                $data["error"] = $lang["ADMINCATEG"]["ERRORNAME"];
                                echo json_encode($data);
                            }
                        }
                        elseif ($_POST["method"] === "add_title"){
                            echo $lang["ADMINCATEG"]["ADDTITLE"];
                        }
                        elseif ($_POST["method"] === "update_title"){
                            echo $lang["ADMINCATEG"]["UPDATETITLE"];
                        }
                    }
                }
                else {
                    echo($lang["ADMINUSERS"]["ERRORPWD"]);
                    die;
                }
            }
            else {
                echo($lang["ADMINUSERS"]["ERRORADMIN"]);
                die;
            }
        } else {
            header('Location: /');
        }
    }
}

