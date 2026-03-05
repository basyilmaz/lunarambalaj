Add-Type -AssemblyName System.Drawing

function FlipImage($source, $dest, $flipTypeString) {
    if (-Not (Test-Path $source)) { return }
    $img = [System.Drawing.Image]::FromFile($source)
    $img.RotateFlip($flipTypeString)
    $ext = [System.IO.Path]::GetExtension($dest).ToLower()
    $format = [System.Drawing.Imaging.ImageFormat]::Jpeg
    if ($ext -eq '.png') { $format = [System.Drawing.Imaging.ImageFormat]::Png }
    $img.Save($dest, $format)
    $img.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images\catalog'

FlipImage "$dir\asset-17.jpg" "$dir\asset-19.jpg" 'RotateNoneFlipX'
FlipImage "$dir\asset-22.jpg" "$dir\asset-24.jpg" 'Rotate180FlipNone'

# Fix the slider
Copy-Item "C:\Users\yimma\.gemini\antigravity\brain\1d0fd1a0-7775-446b-8ab9-76de5237dbfc\asset_01_service_1772308716174.png" -Destination "c:\YazilimProjeler\lunarambalaj\public\images\hero-bg.png" -Force

# Overwrite lifestyle/product images to remove any leftover stock photos
# lifestyle-cocktail
Copy-Item "$dir\asset-08.png" -Destination "c:\YazilimProjeler\lunarambalaj\public\images\lifestyle-cocktail.jpg" -Force

# lifestyle-iced-coffee
Copy-Item "$dir\asset-26.jpg" -Destination "c:\YazilimProjeler\lunarambalaj\public\images\lifestyle-iced-coffee.jpg" -Force

# product-kraft-bag
Copy-Item "$dir\asset-02.png" -Destination "c:\YazilimProjeler\lunarambalaj\public\images\product-kraft-bag.png" -Force

# product-cup-colorful
Copy-Item "$dir\asset-14.jpg" -Destination "c:\YazilimProjeler\lunarambalaj\public\images\product-cup-colorful.png" -Force


Write-Host "Success"
