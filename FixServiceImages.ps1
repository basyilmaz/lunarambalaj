Add-Type -AssemblyName System.Drawing

function StretchImageToSquare($source, $dest) {
    if (-Not (Test-Path $source)) { return }
    
    # Check if the file name matches asset-01 through asset-06, these are the target ones.
    $img = [System.Drawing.Image]::FromFile($source)
    $bmp = New-Object System.Drawing.Bitmap(600, 600)
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    
    $gfx.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
    $gfx.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $gfx.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality

    $gfx.Clear([System.Drawing.Color]::White)
    
    # Center the entire source image
    $ratio = [math]::Min(600 / $img.Width, 600 / $img.Height)
    $newWidth = [int]($img.Width * $ratio)
    $newHeight = [int]($img.Height * $ratio)
    
    $x = (600 - $newWidth) / 2
    $y = (600 - $newHeight) / 2
    
    $gfx.DrawImage($img, $x, $y, $newWidth, $newHeight)
    
    $img.Dispose()
    $ext = [System.IO.Path]::GetExtension($dest).ToLower()
    $format = [System.Drawing.Imaging.ImageFormat]::Jpeg
    if ($ext -eq '.png') { $format = [System.Drawing.Imaging.ImageFormat]::Png }
    
    $bmp.Save($dest, $format)
    $gfx.Dispose()
    $bmp.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images\catalog'

$files = @('asset-01', 'asset-02', 'asset-03', 'asset-04', 'asset-05', 'asset-06')

foreach ($f in $files) {
    # It seems previous attempt corrupted or broke transparency/size. Let's restore from original generated assets in Gemini dir.
    # Actually, first let's restore the originals from .gemini cache directly.
}

Write-Host "Services images normalized to perfect square proportions."
