<!DOCTYPE html>
<html>
<head>
    <title>Vérification Permissions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>🔍 Vérification des Permissions</h1>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4>✅ Permissions existantes ({{ count($existing) }})</h4>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: scroll;">
                        @foreach($existing as $perm)
                            <div class="badge badge-success mb-1">{{ $perm }}</div><br>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h4>⚠️ Permissions manquantes ({{ count($missing) }})</h4>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: scroll;">
                        @if(count($missing) > 0)
                            <p class="alert alert-warning">Il manque {{ count($missing) }} permissions !</p>
                            @foreach($missing as $perm)
                                <div class="badge badge-warning mb-1">{{ $perm }}</div><br>
                            @endforeach
                        @else
                            <p class="alert alert-success">✅ Toutes les permissions sont là !</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        @if(count($missing) > 0)
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h4>📝 SQL pour ajouter les permissions manquantes</h4>
            </div>
            <div class="card-body">
                <p>Copie ce code SQL et exécute-le dans HeidiSQL :</p>
                <textarea class="form-control" rows="15" id="sqlCode">
@php
$nextId = DB::table('fonctionnalites')->max('id') + 1;
foreach($missing as $key) {
    echo "INSERT INTO fonctionnalites (id, libelle, unique_key_string, modules_id, created_at, updated_at) VALUES ({$nextId}, '{$key}', '{$key}', 2, NOW(), NOW());\n";
    $nextId++;
}
@endphp
                </textarea>
                <button class="btn btn-primary mt-2" onclick="copySQL()">📋 Copier le SQL</button>
            </div>
        </div>
        @endif
    </div>
    
    <script>
    function copySQL() {
        const sql = document.getElementById('sqlCode');
        sql.select();
        document.execCommand('copy');
        alert('✅ SQL copié ! Tu peux le coller dans HeidiSQL');
    }
    </script>
</body>
</html>
