<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo Articolo</title>
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/bootstrap.min.css"/>

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/slick.css"/>
    <link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/slick-theme.css"/>

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/nouislider.min.css"/>

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/style.css"/>
</head>
<body>
    {include file='headerSection.tpl'}

    <div class="container mt-5">
        <h2 class="text-center">Crea Articolo</h2>
        <form method="POST" action="/EpTech/admin/saveArticle">
            <div class="form-group">
                <label for="title">Titolo</label>
                <input type="text" class="form-control" id="title" name="title" value="{$article.title|default:''}" required>
            </div>
            <div class="form-group">
                <label for="content">Contenuto</label>
                <textarea class="form-control" id="content" name="content" rows="5" required>{$article.content|default:''}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salva Articolo</button>
        </form>
    </div>

    <!-- Storico degli articoli -->
    <div class="container mt-5">
        <h3 class="text-center">Storico degli Articoli</h3>
        {if $articles|@count > 0}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Contenuto</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$articles item=article}
                    <tr>
                        <td>
                            <a href="#" class="view-article" data-title="{$article.title}" data-content="{$article.content}">
                                {$article.title}
                            </a>
                        </td>
                        <td>{$article.content|truncate:100}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        {else}
            <p class="text-center">Non ci sono articoli disponibili.</p>
        {/if}
    </div>

    <!-- Modale per visualizzare l'articolo -->
    <div class="modal fade" id="articleModal" tabindex="-1" role="dialog" aria-labelledby="articleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="articleModalLabel">Titolo Articolo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="articleContent">Contenuto dell'articolo</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/EpTech/skin/electroMaster/js/jquery.min.js"></script>
    <script src="/EpTech/skin/electroMaster/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Quando si clicca su un articolo
            $('.view-article').on('click', function (e) {
                e.preventDefault(); // Previene il comportamento predefinito del link
    
                // Recupera i dati dall'attributo data
                const title = $(this).data('title');
                const content = $(this).data('content');
    
                // Popola la modale con i dati
                $('#articleModalLabel').text(title);
                $('#articleContent').text(content);
    
                // Mostra la modale
                $('#articleModal').modal('show');
            });
        });
    </script>
</body>
</html>