<?php

/*  PEL: PHP EXIF Library.  A library with support for reading and
 *  writing all EXIF headers of JPEG images using PHP.
 *
 *  Copyright (C) 2004  Martin Geisler <gimpster@users.sourceforge.net>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program in the file COPYING; if not, write to the
 *  Free Software Foundation, Inc., 59 Temple Place, Suite 330,
 *  Boston, MA 02111-1307 USA
 */

/* $Id$ */


class DataWindowTestCase extends UnitTestCase {

  function __construct() {
    require_once('../PelDataWindow.php');
    parent::__construct('PEL Data Window Tests');
  }

  function testReadBytes() {
    $window = new PelDataWindow('abcdefgh');

    $this->assertEqual($window->getSize(), 8);
    $this->assertEqual($window->getBytes(), 'abcdefgh');

    $this->assertEqual($window->getBytes(0), 'abcdefgh');
    $this->assertEqual($window->getBytes(1), 'bcdefgh');
    $this->assertEqual($window->getBytes(7), 'h');
    //$this->assertEqual($window->getBytes(8), '');

    $this->assertEqual($window->getBytes(-1), 'h');
    $this->assertEqual($window->getBytes(-2), 'gh');
    $this->assertEqual($window->getBytes(-7), 'bcdefgh');
    $this->assertEqual($window->getBytes(-8), 'abcdefgh');
    
    $clone = $window->getClone(2, 4);
    $this->assertEqual($clone->getSize(), 4);
    $this->assertEqual($clone->getBytes(), 'cdef');

    $this->assertEqual($clone->getBytes(0), 'cdef');
    $this->assertEqual($clone->getBytes(1), 'def');
    $this->assertEqual($clone->getBytes(3), 'f');
    //$this->assertEqual($clone->getBytes(4), '');

    $this->assertEqual($clone->getBytes(-1), 'f');
    $this->assertEqual($clone->getBytes(-2), 'ef');
    $this->assertEqual($clone->getBytes(-3), 'def');
    $this->assertEqual($clone->getBytes(-4), 'cdef');

    
    $caught = false;
    try {
      $this->assertEqual($clone->getBytes(0, 6), 'cdefgh');
    } catch (PelDataWindowOffsetException $e) {
      $caught = true;
    }
    $this->assertTrue($caught);

  }

  function testReadIntegers() {
    $window = new PelDataWindow("\1\2\3\4", PelConvert::BIG_ENDIAN);

    $this->assertEqual($window->getSize(), 4);
    $this->assertEqual($window->getBytes(), "\1\2\3\4");

    $this->assertEqual($window->getByte(0), 0x01);
    $this->assertEqual($window->getByte(1), 0x02);
    $this->assertEqual($window->getByte(2), 0x03);
    $this->assertEqual($window->getByte(3), 0x04);

    $this->assertEqual($window->getShort(0), 0x0102);
    $this->assertEqual($window->getShort(1), 0x0203);
    $this->assertEqual($window->getShort(2), 0x0304);

    $this->assertEqual($window->getLong(0), 0x01020304);
    
  }
  
}

?>