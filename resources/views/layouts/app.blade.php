<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Meu E-commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (necessário para o AJAX funcionar) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">E-commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Produtos</a>
                    </li>
                </ul>
                <!-- Botões de Scraping e Apagar Produtos -->
                <form class="d-flex" id="scrapeForm" method="GET">
                    @csrf
                    <button class="btn btn-success me-2" type="button" id="scrapeBtn">Realizar Scraping</button>
                </form>
                <form class="d-flex" id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="button" id="deleteBtn">Apagar Todos os Itens</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $('#scrapeBtn').on('click', function() {
            $.ajax({
                url: "{{ route('products.scrape') }}",
                type: 'GET',
                success: function(response) {
                    alert('Scraping realizado com sucesso!');
                    location.reload(); 
                },
                error: function(response) {
                    alert('Erro ao realizar scraping.');
                }
            });
        });

        $('#deleteBtn').on('click', function() {
            $.ajax({
                url: "{{ route('products.deleteAll') }}",
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Todos os itens foram apagados com sucesso!');
                    location.reload(); 
                },
                error: function(response) {
                }
            });
        });
    </script>

</body>
</html>
