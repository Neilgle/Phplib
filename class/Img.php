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

        public static function fopen(){
            self::$fp = fopen(self::$file, "r") or die("Can't open file");
            self::$data = fread(self::$fp,filesize(self::$file));
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

    testToBase64();