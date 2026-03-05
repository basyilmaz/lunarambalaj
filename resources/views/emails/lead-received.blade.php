<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Lead</title></head>
<body>
<h2>New {{ ucfirst($lead->type) }} lead</h2>
<p><strong>Name:</strong> {{ $lead->name }}</p>
<p><strong>Company:</strong> {{ $lead->company }}</p>
<p><strong>Phone:</strong> {{ $lead->phone }}</p>
<p><strong>Email:</strong> {{ $lead->email }}</p>
<p><strong>Message:</strong> {{ $lead->message }}</p>
<p><strong>Meta:</strong> {{ json_encode($lead->meta) }}</p>
</body>
</html>
