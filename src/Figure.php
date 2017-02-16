<?php
/*
 * This file is part of GImage.
 *
 * (c) Jose Luis Quintana <https://git.io/joseluisq>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace GImage;

use GImage\Image;

/**
 * Class to embed simple graphic into the Canvas.
 * A Figure can be a Canvas too.
 *
 * @package GImage
 * @author Jose Luis Quintana <http://git.io/joseluisq>
 *
 * @property int $red Red color
 * @property int $green Green color
 * @property int $blue Blue color
 */
class Figure extends Image
{
    protected $height = 0;
    protected $width = 0;
    protected $red = 0;
    protected $green = 0;
    protected $blue = 0;
    protected $figureType = 'rectangle';

  /**
   * Sets size for figure.
   *
   * @access public
   * @param int $width Width.
   * @param int $height Height.
   * @return void
   */
    public function __construct($width = 0, $height = 0)
    {
        $this->setSize($width, $height);
        $this->toPNG();

        parent::__construct();
    }

  /**
   * Sets size to figure.
   *
   * @access public
   * @param int $width Width.
   * @param int $height Height.
   * @return \GImage\Figure
   */
    public function setSize($width = 0, $height = 0)
    {
        if (!empty($width) && !empty($height)) {
            $this->width = $this->boxWidth = $width;
            $this->height = $this->boxHeight = $height;
        }

        return $this;
    }

  /**
   * Sets the figure type as 'rectangle'.
   *
   * @access public
   * @return \GImage\Figure
   */
    public function isRectangle()
    {
        $this->figureType = 'rectangle';
        return $this;
    }

  /**
   * Sets the figure type as 'ellipse'.
   *
   * @access public
   * @return \GImage\Figure
   */
    public function isEllipse()
    {
        $this->figureType = 'ellipse';
        return $this;
    }

  /**
   * Sets background color.
   *
   * @access public
   * @param int $red Red.
   * @param int $green Green.
   * @param int $blue Blue.
   * @return \GImage\Figure
   */
    public function setBackgroundColor($red, $green, $blue)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        return $this;
    }

  /**
   * Sets background color.
   *
   * @access public
   * @return array An array with RGB colors.
   */
    public function getBackgroundColor()
    {
        return [$this->red, $this->green, $this->blue];
    }

  /**
   * Creates the figure with alpha channel.
   *
   * @access public
   * @return \GImage\Figure
   */
    public function create()
    {
        $figure = imagecreatetruecolor($this->width, $this->height);
        imagesavealpha($figure, true);

        $color = imagecolorallocatealpha(
            $figure,
            $this->red,
            $this->green,
            $this->blue,
            $this->opacity
        );

        $alpha = imagecolorallocatealpha($figure, 255, 255, 255, 127);

        imagefill($figure, 0, 0, $alpha);
        imagefill($figure, $this->width - 1, 0, $alpha);
        imagefill($figure, 0, $this->height - 1, $alpha);
        imagefill($figure, $this->width - 1, $this->height - 1, $alpha);

        if ($this->figureType == 'rectangle') {
            imagefilledrectangle(
                $figure,
                0,
                0,
                $this->width,
                $this->height,
                $color
            );
        }


        if ($this->figureType == 'ellipse') {
            imagefilledellipse(
                $figure,
                $this->width / 2,
                $this->height / 2,
                $this->width,
                $this->height,
                $color
            );
        }

        $this->resource = $figure;
        return $this;
    }
}
