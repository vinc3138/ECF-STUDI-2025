$router->get('/employe', function () {
    if (!Auth::check() || Auth::role() !== 'EMPLOYE') {
        header('Location: /');
        exit;
    }
    (new EmployeController())->dashboard();
});

$router->post('/employe/avis', function () {
    if (!Auth::check() || Auth::role() !== 'EMPLOYE') {
        header('Location: /');
        exit;
    }
    (new EmployeController())->traiterAvis();
});
