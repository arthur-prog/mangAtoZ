<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class AdminProducts{
    public function __invoke()
    {
        startSession();

        $lang = getTextLangue($_SESSION["locale"]);

        if (isset($_SESSION["user"])) {
            $em = EntityManager::getInstance();

            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

            /** @var ProductRepository $productRepository */
            $productRepository = $em->getRepository(Product::class);

            if ($user->isAdmin()) {
                if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                    if (!$_POST) {
                        $products = $productRepository->findBy(array(), array('productName' => 'ASC'));

                        return new Response('/admin/adminProducts.html.twig', ['lang' => $lang, 'products' => $products, 'user' => isUser()]);
                    } else {
                        $product = $productRepository->findOneBy(['productId' => $_POST["id"]]);
                        $product->setNotAvailable(!$product->isNotAvailable());
                        $em->persist($product);
                        $em->flush();


                        $notAvailable = $product->isNotAvailable();
                        $data = [];
                        if ($notAvailable) {
                            $data["btn"] = $lang["ADMINPRODUCTS"]["PUTBACK"];
                            $data["value"] = $lang["ADMINPRODUCTS"]["NO"];
                        } else {
                            $data["btn"] = $lang["ADMINPRODUCTS"]["DELETE"];
                            $data["value"] = $lang["ADMINPRODUCTS"]["YES"];
                        }

                        echo(json_encode($data));
                    }
                } else {
                    echo($lang["ADMINUSERS"]["ERRORPWD"]);
                    die;
                }
            } else {
                echo($lang["ADMINUSERS"]["ERRORADMIN"]);
                die;
            }
        } else {
            header('Location: /');
        }
    }
}

