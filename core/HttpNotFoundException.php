<?php

class HttpNotFoundException extends Exception {};

    try {
        //do something
    }catch(FooException $e) {
        //...
    }catch (BarException $e) {
        //...
    }catch (Exception $e) {
        //Exceptionクラスはすべての例外の親クラスなので、
        //Exceptionを指定するとすべて例外を捕獲
    }