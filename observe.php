 <?PHP
 //先定义一个被观察者的接口，这个接口要实现注册观察者，删除观察者和通知的功能。
 interface Observables
 {
    public function attach(observer $ob);
    public function detach(observer $ob);
    public function notify();
 }

 class Saler implements Observables
 {
    protected $obs = array();       //把观察者保存在这里
    protected $range = 0;

    public function attach(Observer $ob)
    {
        $this->obs[] = $ob;
    }

    public function detach(Observer $ob)
    {
        foreach($this->obs as $o)
        {
            if($o != $ob)
                $this->obs[] = $o;
        }
    }

    public function notify()
    {
        foreach($this->obs as $o)
        {
            $o->doActor($this);
        }
    }

    public function increPrice($range)
    {
        $this->range = $range;
    }

    public function getAddRange()
    {
        return $this->range;
    }
 }


 //定义一个观察者的接口，这个接口要有一个在被通知的时候都要实现的方法
 interface Observer
 {
    public function doActor(Observables $obv);
 }

//为了容易阅读，我在这里增加了一层，定义了一个买家， 之后会有Poor和Rich两种不同的类型继承这个类，用以表示不同类型的买家 
 abstract class Buyer implements Observer
 {
 }

 class PoorBuyer extends Buyer
 {
     //PoorBurer的做法
    public function doActor(observables $obv)
    {
        if($obv->getAddRange() > 10)
            echo  '不买了.<br />';
        else
            echo '还行，买一点吧.<br />';
    }
 }

class RichBuyer extends Buyer
{
    //RichBuyer的做法
    public function doActor(observables $obv)
    {
        echo '你再涨我也不怕，咱不差钱.<br />';
    }
}


$saler = new Saler();  //小贩(被观察者)
$saler->attach(new PoorBuyer()); //注册一个低收入的消费者(观察者)
$saler->attach(new RichBuyer()); //注册一个高收入的消费者(观察者)
$saler->notify(); //通知

$saler->increPrice(12);  //涨价
$saler->notify();  //通知
