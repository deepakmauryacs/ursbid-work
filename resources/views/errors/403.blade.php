<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="text-danger">You have no permission to access this page.</h4>
                    <p class="mt-3">{{ now()->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
