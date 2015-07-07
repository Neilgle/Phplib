<?php
    /**
    * Img
    * - ToBase64
    */
    class Img {

        static $file = '';
        static $type = array();
        static $fp = null;
        static $data = null;

        /**
         * [__construct]
         * @param [string] $file file's dir
         */
        function __construct($file = '', $fopen = false) {

            if($file === ''){
                //
            }else{
                self::$file = $file;
                self::$type = getimagesize(self::$file); //取得图片的大小，类型等

                if($fopen) {
                    self::fopen();
                }else {
                    self::$data = file_get_contents($file);
                }
            }

        }

        /**
         * [ToBase64 根据图片的类型将其编码成base64]
         */
        public function ToBase64() {

            $data = self::$data;
            $file_content=chunk_split(base64_encode($data)); //base64编码

            if(isset(self::$type['mime'])) {
                $img_type = self::$type['mime'];
            }else {
                switch(self::$type[2]){ //判读图片类型
                    case 1:
                        $img_type = "gif";
                        break;
                    case 2:
                        $img_type = "jpg";
                        break;
                    case 3:
                        $img_type = "png";
                        break;
                }
                $img_type = 'image/'.$img_type;
            }

            $img = 'data:'.$img_type.';base64,'.$file_content; //合成图片的base64编码

            return $img; //base64编码图片
        }

        /**
         * [FromBase64 将图片解码成相应格式]
         */
        public function FromBase64($string, $name = "demo", $output = '') {
            // echo $string;
            if(mb_substr($string, 0, 4) == 'data'){
                $ex = explode(';', $string);
                $type = mb_substr($ex[0], 11);
                $string = mb_substr($ex[1], 7);
            }else{
                $type = 'jpg';
            }

            $file_content = base64_decode($string); //base64编码

            file_put_contents($output.$name.'.'.$type, $file_content);
        }

        public static function fopen(){
            self::$fp = fopen(self::$file, "r") or die("Can't open file");
            self::$data = fread(self::$fp,filesize(self::$file));
            return self::$data;
        }

        public static function fclose(){
            fclose(self::$fp);
        }

        public function __destruct(){
            if(self::$fp !== null){
                self::fclose();
            }
        }
    }

    function testToBase64(){

        $file="../src/img/logo.png";

        $img = new Img($file);
        $data = $img->ToBase64();
        echo "<img src='".$data."'>";

    }

    function testFromBase64(){
        $img = new Img();
        $data = $img->FromBase64( file_get_contents('../src/img/base64img.txt'), 'demo', '../src/img/' );

    }

    // testToBase64();
    testFromBase64();