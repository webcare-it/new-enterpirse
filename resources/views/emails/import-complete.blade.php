<!DOCTYPE html>
<html>

<head>
    <title>Product Import Completed</title>
</head>

<body>
    <h2>Product Import Completed</h2>
    <p>Your product import has been completed on {{ $date }}.</p>

    <h3>Summary:</h3>
    <ul>
        <li>Total rows processed: {{ $result['total'] }}</li>
        <li>Successfully imported: {{ $result['success'] }}</li>
        <li>Inserted: {{ $result['inserted'] ?? 0 }}</li>
        <li>Updated: {{ $result['updated'] ?? 0 }}</li>
        <li>Errors: {{ $result['errors'] }}</li>
    </ul>

    @if (($result['errors'] ?? 0) > 0)
        <p>Please check the error log for details.</p>
    @endif

    <p>Thank you!</p>
</body>

</html>
