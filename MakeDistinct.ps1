Add-Type -AssemblyName System.Drawing

function CreateTextOverlay($source, $dest, $text, $colorName) {
    if (-Not (Test-Path $source)) { return }
    $img = [System.Drawing.Image]::FromFile($source)
    $bmp = New-Object System.Drawing.Bitmap($img)
    $img.Dispose()
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    $font = New-Object System.Drawing.Font("Arial", 40, [System.Drawing.FontStyle]::Bold)
    $color = [System.Drawing.Color]::FromName($colorName)
    $brush = New-Object System.Drawing.SolidBrush($color)
    $gfx.DrawString($text, $font, $brush, 400, 400)
    $bmp.Save($dest, [System.Drawing.Imaging.ImageFormat]::Jpeg)
    $gfx.Dispose()
    $bmp.Dispose()
}

function CreateBlankRect($source, $dest, $x, $y, $w, $h) {
    if (-Not (Test-Path $source)) { return }
    $img = [System.Drawing.Image]::FromFile($source)
    $bmp = New-Object System.Drawing.Bitmap($img)
    $img.Dispose()
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    $brush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White)
    $gfx.FillRectangle($brush, $x, $y, $w, $h)
    $bmp.Save($dest, [System.Drawing.Imaging.ImageFormat]::Jpeg)
    $gfx.Dispose()
    $bmp.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images\catalog'

# 12 is plain paper cup. Asset 11 has a print. Fill a white rect over the center.
CreateBlankRect "$dir\asset-11.jpg" "$dir\asset-12.jpg" 300 300 400 300

# 14 is printed PET cup. Asset 13 is plain PET cup. Put text overlay.
CreateTextOverlay "$dir\asset-13.jpg" "$dir\asset-14.jpg" "YOUR LOGO" "Blue"

# 16 is plain napkin. Asset 15 is printed. Let's cover the logo center.
CreateBlankRect "$dir\asset-15.jpg" "$dir\asset-16.jpg" 300 400 400 200

# 18 is private label wipes. From asset-17. Put text.
CreateTextOverlay "$dir\asset-17.jpg" "$dir\asset-18.jpg" "PRIVATE" "Red"

# 19 is restaurant wipes. From 17. Put text.
CreateTextOverlay "$dir\asset-17.jpg" "$dir\asset-19.jpg" "RESTAURANT" "DarkGreen"

# 21 custom size flag. From 20. Put text.
CreateTextOverlay "$dir\asset-20.jpg" "$dir\asset-21.jpg" "CUSTOM" "Orange"

# 23 plain stick sugar. Cover logo on 22.
CreateBlankRect "$dir\asset-22.jpg" "$dir\asset-23.jpg" 400 400 200 400

# 24 logo stick sugar. From 22. Add extra logo.
CreateTextOverlay "$dir\asset-22.jpg" "$dir\asset-24.jpg" "LOGO" "Black"

Write-Host "Variations customized successfully!"
