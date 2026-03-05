Add-Type -AssemblyName System.Drawing

function CreateVariation($source, $dest, $action) {
    if (-Not (Test-Path $source)) { return }
    $img = [System.Drawing.Image]::FromFile($source)
    $bmp = New-Object System.Drawing.Bitmap($img)
    $img.Dispose()
    
    if ($action -eq 'flip') {
        $bmp.RotateFlip([System.Drawing.RotateFlipType]::RotateNoneFlipX)
    } elseif ($action -eq 'crop') {
        $rect = New-Object System.Drawing.Rectangle(50, 50, $bmp.Width - 100, $bmp.Height - 100)
        $cropped = $bmp.Clone($rect, $bmp.PixelFormat)
        $bmp.Dispose()
        $bmp = $cropped
    } elseif ($action -eq 'grayscale') {
        for ($y = 0; $y -lt $bmp.Height; $y++) {
            for ($x = 0; $x -lt $bmp.Width; $x++) {
                $c = $bmp.GetPixel($x, $y)
                $gray = [int](($c.R * 0.3) + ($c.G * 0.59) + ($c.B * 0.11))
                $bmp.SetPixel($x, $y, [System.Drawing.Color]::FromArgb($c.A, $gray, $gray, $gray))
            }
        }
    }
    
    $ext = [System.IO.Path]::GetExtension($dest).ToLower()
    $format = [System.Drawing.Imaging.ImageFormat]::Jpeg
    if ($ext -eq '.png') { $format = [System.Drawing.Imaging.ImageFormat]::Png }
    
    $bmp.Save($dest, $format)
    $bmp.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images\catalog'

# asset-12 (Baskısız Karton Bardak) from product-cup-white.png
CreateVariation 'c:\YazilimProjeler\lunarambalaj\public\images\product-cup-white.png' "$dir\asset-12.jpg" 'none'
# asset-14 (Baskılı PET Bardak) from product-cup-colorful.png
CreateVariation 'c:\YazilimProjeler\lunarambalaj\public\images\product-cup-colorful.png' "$dir\asset-14.jpg" 'none'

# asset-16 (Baskısız Peçete) from asset-15 (flip)
CreateVariation "$dir\asset-15.jpg" "$dir\asset-16.jpg" 'flip'

# asset-18 (Özel Marka Islak Mendil) from asset-17 (flip)
CreateVariation "$dir\asset-17.jpg" "$dir\asset-18.jpg" 'flip'

# asset-19 (Restoran Tipi Islak Mendil) from asset-17 (crop)
CreateVariation "$dir\asset-17.jpg" "$dir\asset-19.jpg" 'crop'

# asset-21 (Özel Ölçü Bayraklı Kürdan) from asset-20 (flip)
CreateVariation "$dir\asset-20.jpg" "$dir\asset-21.jpg" 'flip'

# asset-23 (Baskısız Stick Şeker) from asset-22 (flip)
CreateVariation "$dir\asset-22.jpg" "$dir\asset-23.jpg" 'flip'

# asset-24 (Logolu Stick Şeker) from asset-22 (crop)
CreateVariation "$dir\asset-22.jpg" "$dir\asset-24.jpg" 'crop'

# asset-26 (Blog 2) from lifestyle-cocktail
CreateVariation 'c:\YazilimProjeler\lunarambalaj\public\images\lifestyle-cocktail.jpg' "$dir\asset-26.jpg" 'none'

# asset-27 (Blog 3) from lifestyle-iced-coffee
CreateVariation 'c:\YazilimProjeler\lunarambalaj\public\images\lifestyle-iced-coffee.jpg' "$dir\asset-27.jpg" 'none'

Write-Host "Image variations generated."
