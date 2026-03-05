Add-Type -AssemblyName System.Drawing

function ReplaceAlphaWithColor($source, $dest, $bgName) {
    if (-Not (Test-Path $source)) { return }
    $img = [System.Drawing.Image]::FromFile($source)
    $bmp = New-Object System.Drawing.Bitmap($img.Width, $img.Height)
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    $color = [System.Drawing.Color]::FromName($bgName)
    $gfx.Clear($color)
    $gfx.DrawImage($img, 0, 0)
    $img.Dispose()
    $bmp.Save($dest, [System.Drawing.Imaging.ImageFormat]::Png)
    $gfx.Dispose()
    $bmp.Dispose()
}

$dir = 'c:\YazilimProjeler\lunarambalaj\public\images'

ReplaceAlphaWithColor "$dir\category-straws.svg" "$dir\category-straws.svg" "White"
