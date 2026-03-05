Add-Type -AssemblyName System.Drawing

function CreateTextPlaceholder($dest, $text, $bgColorName) {
    $bmp = New-Object System.Drawing.Bitmap(600, 400)
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    $gfx.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
    
    $color = [System.Drawing.Color]::FromName($bgColorName)
    $gfx.Clear($color)
    
    $font = New-Object System.Drawing.Font("Arial", 40, [System.Drawing.FontStyle]::Bold)
    $brush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White)
    
    # Very simple centering
    $gfx.DrawString($text, $font, $brush, 50, 150)
    
    $bmp.Save($dest, [System.Drawing.Imaging.ImageFormat]::Jpeg)
    $gfx.Dispose()
    $bmp.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images'

CreateTextPlaceholder "$dir\ref-horeca.jpg" "Cafee & Horeca" "DarkSlateGray"
CreateTextPlaceholder "$dir\ref-hotel.jpg" "Hotels" "Teal"
CreateTextPlaceholder "$dir\ref-retail.jpg" "Retail Boxes" "SaddleBrown"
CreateTextPlaceholder "$dir\ref-fastfood.jpg" "Fast Food" "DarkRed"
CreateTextPlaceholder "$dir\ref-catering.jpg" "Catering Events" "Navy"
CreateTextPlaceholder "$dir\ref-wholesale.jpg" "Wholesale B2B" "MidnightBlue"
