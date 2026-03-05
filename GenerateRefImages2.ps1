Add-Type -AssemblyName System.Drawing

function CreateAbstractImage($dest, $colorName, $text) {
    $bmp = New-Object System.Drawing.Bitmap(600, 400)
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    $gfx.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
    
    $color = [System.Drawing.Color]::FromName($colorName)
    $gfx.Clear($color)
    
    # Draw some abstract geometric patterns to look active/modern
    $pen = New-Object System.Drawing.Pen([System.Drawing.Color]::FromArgb(40, 255, 255, 255), 3)
    $gfx.DrawLine($pen, 0, 100, 600, 300)
    $gfx.DrawLine($pen, 600, 0, 0, 400)
    $gfx.DrawRectangle($pen, 100, 50, 400, 300)
    
    $font = New-Object System.Drawing.Font("Arial", 40, [System.Drawing.FontStyle]::Bold)
    $font2 = New-Object System.Drawing.Font("Arial", 20, [System.Drawing.FontStyle]::Regular)
    $brush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White)
    $brushYellow = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::Gold)
    
    $gfx.DrawString($text, $font, $brush, 30, 150)
    $gfx.DrawString("Supply Solutions", $font2, $brushYellow, 30, 220)
    
    $bmp.Save($dest, [System.Drawing.Imaging.ImageFormat]::Jpeg)
    $pen.Dispose()
    $gfx.Dispose()
    $bmp.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images'

CreateAbstractImage "$dir\ref-horeca.jpg" "DarkSlateGray" "HORECA"
CreateAbstractImage "$dir\ref-hotel.jpg" "Teal" "Hotel Groups"
CreateAbstractImage "$dir\ref-retail.jpg" "SaddleBrown" "Retail Supply"
CreateAbstractImage "$dir\ref-fastfood.jpg" "DarkRed" "Fast Food"
CreateAbstractImage "$dir\ref-catering.jpg" "Indigo" "Catering & Event"
CreateAbstractImage "$dir\ref-wholesale.jpg" "MidnightBlue" "B2B Wholesale"
