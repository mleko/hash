<?php

namespace Mleko\Hash\Tests;

class HashTest extends \PHPUnit_Framework_TestCase
{

    public function hashProvider()
    {
        return [
            ['md5', "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa", "61e39aae7c53e6e77db2e4405d9fb157"],
            ['sha1', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '70c0661629f61a1d0c4f46d955e9bb2364077196'],
            ['sha1', 'dec638026698faad570641ea912b91f9ba396a2c', 'c793fe253a4822946ce1a6ff8579c7261f318146'],
            ['md5', 'dec638026698faad570641ea912b91f9ba396a2c', '31481769503689600fd9aaa5114e5f87']
        ];
    }

    /**
     * @dataProvider hashProvider
     */
    public function testHashString($algo, $string, $expected)
    {
        $this->assertEquals(\Mleko\Hash\Hash::hashString($algo, $string), $expected);
    }

    /**
     * @dataProvider hashProvider
     */
    public function testUpdate($algo, $string, $expected)
    {
        $hash = new \Mleko\Hash\Hash($algo);
        $hash->update($string);
        $this->assertEquals($hash->result(), $expected);
    }

    /**
     * @dataProvider hashProvider
     */
    public function testPartialUpdate($algo, $string, $expected)
    {
        $hash = new \Mleko\Hash\Hash($algo);
        foreach (str_split($string, 7) as $part) {
            $hash->update($part);
        }
        $this->assertEquals($hash->result(), $expected);
    }

    /**
     * @dataProvider hashProvider
     */
    public function testCopy($algo, $string, $expected)
    {
        $hash = new \Mleko\Hash\Hash($algo);
        $copy = $hash->copy();
        foreach (str_split($string, 7) as $part) {
            $hash->update($part);
        }
        $this->assertEquals($hash->result(), $expected);
        $this->assertNotEquals($hash->result(), $copy->result());

        $copy->update($string);
        $this->assertEquals($copy->result(), $expected);
    }
}