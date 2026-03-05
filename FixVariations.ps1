Add-Type -AssemblyName System.Drawing

function FlipImage($source, $dest, $flipType) {
    if (-Not (Test-Path $source)) { return }
    $img = [System.Drawing.Image]::FromFile($source)
    $img.RotateFlip($flipType)
    $ext = [System.IO.Path]::GetExtension($dest).ToLower()
    $format = [System.Drawing.Imaging.ImageFormat]::Jpeg
    if ($ext -eq '.png') { $format = [System.Drawing.Imaging.ImageFormat]::Png }
    $img.Save($dest, $format)
    $img.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images\catalog'

FlipImage "$dir\asset-17.jpg" "$dir\asset-19.jpg" [System.Drawing.RotateFlipType]::RotateNoneFlipX
FlipImage "$dir\asset-22.jpg" "$dir\asset-24.jpg" [System.Drawing.RotateFlipType]::RotateNoneFlipY

Write-Host "Done"
