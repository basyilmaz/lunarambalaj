Add-Type -AssemblyName System.Drawing

function CreateSquareCrop($source, $dest) {
    if (-Not (Test-Path $source)) { return }
    $img = [System.Drawing.Image]::FromFile($source)
    $bmp = New-Object System.Drawing.Bitmap($img)
    $img.Dispose()
    
    $size = [math]::Min($bmp.Width, $bmp.Height)
    $x = ($bmp.Width - $size) / 2
    $y = ($bmp.Height - $size) / 2
    
    $rect = New-Object System.Drawing.Rectangle($x, $y, $size, $size)
    $cropped = $bmp.Clone($rect, $bmp.PixelFormat)
    $bmp.Dispose()
    
    $ext = [System.IO.Path]::GetExtension($dest).ToLower()
    $format = [System.Drawing.Imaging.ImageFormat]::Jpeg
    if ($ext -eq '.png') { $format = [System.Drawing.Imaging.ImageFormat]::Png }
    
    $cropped.Save($dest, $format)
    $cropped.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images\catalog'

$files = @('asset-01', 'asset-02', 'asset-03', 'asset-04', 'asset-05', 'asset-06')

foreach ($f in $files) {
    $source = "$dir\$f.png"
    if (Test-Path $source) {
        CreateSquareCrop $source $source
    }
}

Write-Host "Services images cropped to square."
