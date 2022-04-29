<?php

require_once('../app/core/controller.php');

class Admin extends Controller
{
    /**
     * index
     * Load the User model and the admin/index view
     * @return admin/index view
     */
    public function index()
    {
        $user = $this->loadModel('User');
        $userData = $user->checkLogin(['admin']);
        if (is_object($userData)) {
            $data['userData'] = $userData;
        }
        $data['pageTitle'] = "Admin - Home";
        $this->view("admin/index", $data);
    }

    /**
     * categories
     * Load the User model and the admin/categories view
     * @return admin/categories view
     */
    public function categories()
    {
        $user = $this->loadModel('User');
        $userData = $user->checkLogin(['admin']);

        if (is_object($userData)) {
            $data['userData'] = $userData;
        }

        // get all the categories and the HTML table
        $category = $this->loadModel('Category');
        $allCategories = $category->getAll();
        $tableHTML = $category->makeTable($allCategories);
        $noCat = "";

        if (strlen($tableHTML == "")) {
            $noCat =  "<p class='text-center'>Vous n'avez aucune categorie. Vous devez en ajouter au moins une pour créer un livre !</p>";
        }

        $data['noCat'] = $noCat;
        $data['tableHTML'] = $tableHTML;
        $data['pageTitle'] = "Admin - Categories";
        $this->view("admin/categories", $data);
    }

    /**
     * categories
     * Load the User model and the admin/categories view
     * @return admin/categories view
     */
    public function products($method = false, $arg = "")
    {
        $user = $this->loadModel('User');
        $userData = $user->checkLogin(['admin']);

        if (is_object($userData)) {
            $data['userData'] = $userData;
        }

        $product = $this->loadModel('Product');

        if ($method === "add") {
            $this->addProduct($data, $product);
        } elseif ($method === "update") {
            $this->updateProduct($data, $product,  $arg);
        } elseif ($method === "home") {
            // get all the products and the HTML table
            $allProducts = $product->getAllProducts();
            $tableHTML = $product->makeTable($allProducts);
            $noProd = "";

            if (strlen($tableHTML == "")) {
                $noProd =  "<p class='text-center'>Vous n'avez aucun livre. Vous devez avoir au moins une catégorie pour créer un livre !</p>";
            }

            $data['noProd'] = $noProd;
            $data['tableHTML'] = $tableHTML;
            $data['pageTitle'] = "Admin - Products";
            $this->view("admin/products", $data);
        }
    }

    /**
     * commands
     * display the users commands
     * @return view admin/commands
     */
    public function commands()
    {
        $user = $this->loadModel('User');
        $userData = $user->checkLogin(['admin']);

        if (is_object($userData)) {
            $data['userData'] = $userData;
        }

        $commandModel = $this->loadModel("CommandModel");
        $allCommands = $commandModel->getAllCommands();
        $commandsHTML = $commandModel->makeTable($allCommands);
        $noCom = "";

        if (strlen($commandsHTML == "")) {
            $noCom =  "<p class='text-center'>Aucun client n'a passé de commande !</p>";
        }

        $data['noCom'] = $noCom;
        $data['commandsHTML'] = $commandsHTML;
        $data['pageTitle'] = "Admin - Commandes";
        $this->view("admin/commands", $data);
    }

    /**
     * addProduct
     *Load the admin/products/add view
     * @param  array $data
     * @return admin/products/add view
     */
    public function addProduct($data, $productModel)
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $productModel->create();
            header("Location: " . ROOT . "admin/products");
        }

        // get all the categories for the select in the form addProduct
        $category = $this->loadModel('Category');
        $allCategories = $category->getAll();
        $selectHTML = $productModel->makeSelectCategories($allCategories);

        if ($selectHTML == "") {
            header("Location: " . ROOT . "admin/categories");
        }

        $data['selectHTML'] = $selectHTML;
        $data['categories'] = $allCategories;
        $data['pageTitle'] = "Admin - Add Product";
        $this->view("admin/addProduct", $data);
    }

    /**
     * deleteProduct
     * delete one product in the BDD  
     * @param  int $idProduct
     * @return void
     */
    public function deleteProduct($idProduct)
    {
        $user = $this->loadModel('User');
        $userData = $user->checkLogin();

        if (is_object($userData)) {
            $data['userData'] = $userData;
        }
        //get the datas about the produt
        $product = $this->loadModel('Product');
        $product->deleteProduct($idProduct);
    }

    /**
     * updateProduct
     * Update one product in the BDD
     * @param  arrays $data
     * @param  object $product
     * @param  int $idProduct
     * @return view admin/updateProduct
     */
    public function updateProduct($data, $product, $idProduct)
    {
        $singleProduct  = $product->getOneProduct($idProduct);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $singleProduct  = $product->getOneProduct($idProduct);
            $product->updateProduct($singleProduct[0]->idProduct);
        }

        // get the datas about the produt
        $category = $this->loadModel('Category');
        $allCategories = $category->getAll();
        $selectHTML = $product->makeSelectCategories($allCategories);
        $data['selectHTML'] = $selectHTML;
        $data['categories'] = $allCategories;
        $data['product'] = $singleProduct[0];
        $data['pageTitle'] = "Admin - update Product";
        $this->view("admin/updateProduct", $data);
    }

    /**
     * users
     * read and display all the users
     * @return view admin/users
     */
    public function users($method = false, $arg = "")
    {
        $user = $this->loadModel('User');
        $userData = $user->checkLogin();

        if (is_object($userData)) {
            $data['userData'] = $userData;
        }

        if ($method === "viewAdmins") {
            $this->viewAdmins($data, $user);
        } elseif ($method === "viewCustomers") {
            $this->viewCustomers($data, $user);
        } elseif ($method === "home") {
            $allUsers = $user->getAllUsers();
            $usersHTML = $user->makeTableUsers($allUsers);
            $noCus = "";
            $data['noCus'] = $noCus;
            $data['users'] = $usersHTML;
            $data['pageTitle'] = "Admin - Users";
            $this->view("admin/users", $data);
        }
    }

    /**
     * viewAdmins
     * display all admins users
     * @param  array $data
     * @param  object $user
     * @return view admin/users
     */
    public function viewAdmins($data, $user)
    {
        $allAdmins = $user->getAllAdmins();
        $adminsHTML = $user->makeTableUsers($allAdmins);
        $data['users'] = $adminsHTML;
        $data['pageTitle'] = "Admin - Views Admins";
        $noCus = "";

        $data['noCus'] = $noCus;
        $this->view("admin/users", $data);
    }

    /**
     * viewCustomers
     * display all customers users
     * @param  array $data
     * @param  object $user
     * @return view admin/users
     */
    public function viewCustomers($data, $user)
    {
        $allCustomers = $user->getAllCustomers();
        $customersHTML = $user->makeTableUsers($allCustomers);

        $noCus = "";

        if (strlen($customersHTML == "")) {
            $noCus =  "<p class='text-center'>Vous n'avez aucun client inscrit dans votre site ! Il fallait penser au référencement ;)</p>";
        }

        $data['noCus'] = $noCus;
        $data['users'] = $customersHTML;
        $data['pageTitle'] = "Admin - Views Customers";
        $this->view("admin/users", $data);
    }
}
