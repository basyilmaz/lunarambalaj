<!doctype html>
<html lang="tr">
<head><meta charset="utf-8"><title>Yeni Talep</title></head>
<body>
<h2>Yeni {{ $lead->type === 'quote' ? 'Teklif' : 'İletişim' }} Talebi</h2>
<p><strong>Ad Soyad:</strong> {{ $lead->name }}</p>
<p><strong>Firma:</strong> {{ $lead->company ?: '-' }}</p>
<p><strong>Telefon:</strong> {{ $lead->phone ?: '-' }}</p>
<p><strong>E-posta:</strong> {{ $lead->email }}</p>
<p><strong>Mesaj:</strong> {{ $lead->message ?: '-' }}</p>
<p><strong>Detay (meta):</strong> {{ json_encode($lead->meta, JSON_UNESCAPED_UNICODE) }}</p>
</body>
</html>
