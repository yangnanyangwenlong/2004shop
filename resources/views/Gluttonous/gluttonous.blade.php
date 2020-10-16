<!DOCTYPE html>
<html>

<head>
    <title>贪吃蛇</title>
    <meta charset="utf-8">
    <script type="text/javascript" src='js/jquery-3.2.1.min.js'></script>
    <!-- 复制粘贴时记得引用Jquery库哟！ -->
    <style type="text/css">
    div {
        width: 18px;
        height: 18px;
        float: left;
        border: 1px solid #eee;
    }
    
    .out {
        background: #f00;
    }
    
    .in {
        background: #0f0;
    }
    
    .snake {
        background: #00f;
    }
    
    .food {
        background: #f0f;
    }
    </style>
</head>

<body>
    <div style="width:300px; height: 300px;" id="demo">
    </div>
    <script type="text/javascript">
    var arryRoad = new Array();//创建一个可用方格的数组，这个数组存放的内容是贪吃蛇的“食物”可以出现的位置。
    var arrSnake = new Array();//创建一个贪吃蛇数组，这里存放着贪吃蛇身体的数据。它每一格身体的位置。

    function bulid(num, outStyle, inStyle) {//这个bulid函数用于创建我们的地图（传入的参数：num-->横向个数和纵向个数；outStyle--->外墙样式，inStyle-->内部方格样式）
        for (var i = 1; i <= num * num; i++) {//使用for循环进行创建Div格子
            if (i <= num - 1 || i > num * (num - 1)) {//这里我们做的判断是创建的盒子是不是第一排或者最后一排的，因为他们是我们的外墙，需要单独区分。
                $('<div class="' + outStyle + '">' + i + '</div>').appendTo($('#demo'));//我们把外墙样式加给我们创建的div，并且我们将它添加至#demo之中。
            } else if (i % num == 0 || i % num == 1) {//这个判断是进行处理两边的外墙的，我们判断我们所要创建的div是不是每一横排的第一个或者是最后一个，
                $('<div class="' + outStyle + '">' + i + '</div>').appendTo($('#demo'));//我们把外墙样式加给我们创建的div，并且我们将它添加至#demo之中。
            } else {//那么剩下的Div就是我们内部的方格啦！！！
                $('<div class="' + inStyle + '">' + i + '</div>').appendTo($('#demo'));//我们把内部方格样式加给了我们创建的Div,并且我们将它添加至#demo之中。
                arryRoad.push(i - 1);//然后我们把它的索引值添加到了一开始我们所创建的可用方格数组之中。
                // 需要注意的是，我们为什么会采用i-1而不是i？我们可以回过去看我们for循环内的条件，我们在开头声明的i的初始值是1，并非为0。这么做的原因是为了方便我们计算出外墙。
                //数组的索引值是从0开始的，所以我们需要进行i-1。
            }
        }
    }


    bulid(15, 'out', 'in');//我们在这里调用bulid函数，并且将参数传给了他。让它按照我们一开始预定的想法去执行。
    arrSnake = [17];//我们给蛇确定了他的身体部分，这里，我们可以随意的添加它的长度，但是一定要注意数组的连续性。也不要超过我们的墙的范围。
    var snake = new Object();//我们进行实例化了一个snake对象。
    snake.direction = 'right';//给snake创建了一个方向，让他默认像右移动。
    snake.timmer = null;//我们还给蛇创建了一个计时器。这个计时器肯定用来让我们的贪吃蛇自己运动的啦！


    function move(direction) {//这里我们创建了一个移动函数，来使我们的贪吃蛇动起来~
        var num = 0;//我们先定义一个变量，这变量是用来确定贪吃蛇移动的位置的。
        switch (direction) {//我们用swich，case来进行判断我们的方向
            case 'left':
                num = -1;//向左是-1；
                break;
            case 'right':
                num = 1;//向右是+1；
                break;
            case 'up':
                num = -15;//向上是-15，为什么是-15呢？因为我们一横排是15个div，那么在这个div的上面的一个div的索引就是当前div的索引-15。这里我偷了一个懒，这里的-15应当由一个变量来替代，会更加的恰当，和代码的通用性，如果需要对我的代码进行升级改编，这里肯定是不可省略的。
                break;
            case 'down':
                num = 15;//向下是+15，同上~
                break;
        }
        var zb = parseInt(arrSnake[0]) + num;//我们申明一个变量zb，这个坐标是移动的一下个点的位置
        var itclassName = $('#demo').children().eq(zb).attr("class").toString();//我们去获取下一个点的类名。
        if (itclassName == "out" || itclassName == 'snake') {//在这里，我们判断，下一个点是不是外墙，或者是贪吃蛇自己。

            alert('You die');//如果是的话~ 那么你就GG啦～
            clearInterval(snake.timmer);//别忘了清除计时器，让我们的贪吃蛇停止运动~
            return;//return，跳出函数，下面的代码将不再执行
        } else if (itclassName == 'food') {//我们来判断下一个点是不是我们的贪吃蛇的‘食物’呢？
            $('#demo').children().eq(zb).removeClass(); //移除目标位置所有样式
            $('#demo').children().eq(zb).addClass('snake'); //为目标位置添加样式snake，让他变成我们的一部分
            arrSnake.unshift(zb);//我们将这个点加入到我们贪吃蛇的身体中
            console.log('蛇长度' + arrSnake.length);//这里的console是为了方便我们看到贪吃蛇的长度~，可有可无，也可以进行升级。放在我们的body中显示或者放在网页的title中
            var indxofZb = arryRoad.indexOf(zb); //获取目标位置在可用位置数组中的索引
            arryRoad.splice(indxofZb, 1);//然后，我们将它删除~，因为他现在已经不可用啦！他已经变成了贪吃蛇的一部分了~
            food();//这里我们调用了food函数用来创建贪吃蛇的下一顿美食~
            return;//跳出函数，下面的代码将不再执行
        }


        var lastzb = arrSnake[arrSnake.length - 1]; //同上
        $('#demo').children().eq(zb).removeClass(); //同上**
        $('#demo').children().eq(zb).addClass('snake'); //同上**

        arrSnake.unshift(zb); //同上**

        var indxofZb = arryRoad.indexOf(zb); //同上**
        arryRoad.splice(indxofZb, 1); //同上**



        $('#demo').children().eq(lastzb).removeClass(); //删除蛇尾的所有样式**
        $('#demo').children().eq(lastzb).addClass('in'); //将原本是贪吃蛇的尾巴重新变成了可用位置。**
        arrSnake.splice(arrSnake.length - 1, 1); //然后我们将最后一个位置从我们的贪吃蛇数组中删除~因为他已经不再属于我们的贪吃蛇了**

        arryRoad.push(lastzb); //把最arrSnake中的最后一位添加到arryRoad中，因为它已经变成了一个可用位置


        //这里我需要强调的一个思想，就是贪吃蛇位移的方法，有些人可能想到的是，将我们的头的位置变成目标点，将其他的部分依次变成上一个的位置。这种方法虽然也能达到让贪吃蛇运动的目的，但是这种方法增加了一些不必要的操作。如果我们细心观察，会发现，贪吃蛇的每次运动，除了头和尾部的变化，中间部分都没有发生任何变化。

        //那么，这就给了我们一个启发：我们每次只要改变他的头和尾部样式不久可以了吗?上面打上‘**’标记的正是我所说的方法。
        //这种方法还有另外的一种应用，那就是鼠标移动跟随的流星效果，下一次的博客中，我将分享这种方法的另一种应用。



    }


    $(window).keydown(function(event) {//当鼠标按下时，传入事件对象~
        switch (event.keyCode) {//获取键值码
            case 37://如果键值码为37，代表着我的键盘上的左键
                if (snake.direction != 'right') {//判断是否当前的方向为右，因为，我们的贪吃蛇不能回头~
                    snake.direction = 'left';//如果不是的话，我们将方向改为左
                }
                break;//跳出swich

                //下面的case语句意思基本雷同，我将不做太多的解说


            case 38:

                if (snake.direction != 'down') {
                    snake.direction = 'up';
                }

                break;
            case 39:
                if (snake.direction != 'left') {
                    snake.direction = 'right';
                }

                break;
            case 40:
                if (snake.direction != 'up') {
                    snake.direction = 'down';
                }
                break;
        }

    })


    function autoMove() {//让蛇自动移动的函数~
        snake.timmer = setInterval(function() {//我们创建一个计时器~
            move(snake.direction);//目的就是每隔200毫秒调用move函数让贪吃蛇运动起来，并且我们将方向也传递给了他
        }, 200)
    }


    function randomXY(arryRoad) {//产生随机数函数~
        var x = parseInt(Math.random() * arryRoad.length);//我们产生一个随机数，这个随机数的范围为0~数组长度之间，保证了产生的随机数对应的方格是可用的~
        return arryRoad[x];//我们返回数组中的随机位置的值
    }

    function food() {//这就是我们之前看到的创建食物的函数
        var num = randomXY(arryRoad);//我们声明一个num变量去接收随机数
        $('#demo').children().eq(num).removeClass('in');//我们在#demo中去寻找随机索引的div，我们移除它的内部方格样式
        $('#demo').children().eq(num).addClass('food');//并且给他添加了‘食物’样式
    }


    function init() {//初始化函数
        for (var i = 0; i < arrSnake.length; i++) {//我们循环遍历贪吃蛇数组。
            $('#demo').children().eq(arrSnake[i]).removeClass('in');//移出对应的内部方格样式
            $('#demo').children().eq(arrSnake[i]).addClass('snake');//给每一个元素都添加上一个贪吃蛇样式
        }
        arryRoad.splice(0, arrSnake.length+1);//然后，我们删除了可用数组中的属于蛇的部分

        food();//给我们的贪吃蛇生产出一个食物~

        autoMove();//调用贪吃蛇自动运动函数

    }


    init();//调用初始化函数

    // 到这里我们的整个贪吃蛇的编程就已经完成了，我们可以来看看最终的效果

    // 谢谢收看~

    // by---->SuperZu
    </script>
</body>

</html>