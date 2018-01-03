<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class TagsTest extends TestCase
{


    public function testComment()
    {
        $text = 'some comment';
        $expected1 = '<!--some comment-->';
        $expected2 = '<!-- some comment -->';
        $this->assertEquals($expected1, Tags::comment($text));
        $this->assertEquals($expected2, Tags::comment($text, true));
    }

    public function testDoctype()
    {
        $expected = '<!DOCTYPE html>';
        $this->assertEquals($expected, Tags::doctype());
    }

    public function testA()
    {
        $expected = '<a href="http://msworks.pl/" class="test" title="">MS Works</a>';
        $this->assertEquals($expected, Tags::a('MS Works', ['href' => 'http://msworks.pl/', 'class' => 'test']));
    }

    public function testAbbr()
    {
        $expected = '<abbr title="test">text</abbr>';
        $this->assertEquals($expected, Tags::abbr('text', ['title' => 'test']));
    }

    public function testAddress()
    {
        $expected = '<address class="test">text</address>';
        $this->assertEquals($expected, Tags::address('text', ['class' => 'test']));
    }

    public function testArea()
    {
        $expected = '<area class="test"/>';
        $this->assertEquals($expected, Tags::area(['class' => 'test']));
    }

    public function testArticle()
    {
        $expected = '<article class="test">text</article>';
        $this->assertEquals($expected, Tags::article('text', ['class' => 'test']));
    }

    public function testAside()
    {
        $expected = '<aside class="test">text</aside>';
        $this->assertEquals($expected, Tags::aside('text', ['class' => 'test']));
    }

    public function testAudio()
    {
        $expected = '<audio class="test">text</audio>';
        $this->assertEquals($expected, Tags::audio('text', ['class' => 'test']));
    }

    public function testB()
    {
        $expected = '<b class="test">text</b>';
        $this->assertEquals($expected, Tags::b('text', ['class' => 'test']));
    }

    public function testBase()
    {
        $expected = '<base href="http://msworks.pl/" class="test"/>';
        $this->assertEquals($expected, Tags::base(['href' => 'http://msworks.pl/', 'class' => 'test']));
    }

    public function testBdi()
    {
        $expected = '<bdi class="test">text</bdi>';
        $this->assertEquals($expected, Tags::bdi('text', ['class' => 'test']));
    }

    public function testBdo()
    {
        $expected = '<bdo class="test" dir="ltr">text</bdo>';
        $this->assertEquals($expected, Tags::bdo('text', ['class' => 'test']));
    }

    public function testBlockquote()
    {
        $expected = '<blockquote class="test">text</blockquote>';
        $this->assertEquals($expected, Tags::blockquote('text', ['class' => 'test']));
    }

    public function testBody()
    {
        $expected = '<body class="test">'.Tags::CRLF.'text'.Tags::CRLF.'</body>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::body('text', ['class' => 'test', 'alink' => 'left', 'background' => 'none', 'bgcolor' => '#fff', 'link' => '#aaa', 'text' => '#000', 'vlink' => '#ddd']));
    }

    public function testBr()
    {
        $expected = '<br class="test"/>';
        $this->assertEquals($expected, Tags::br(['class' => 'test']));
    }

    public function testButton()
    {
        $expected = '<button class="test">text</button>';
        $this->assertEquals($expected, Tags::button('text', ['class' => 'test']));
    }

    public function testCanvas()
    {
        $expected = '<canvas class="test">text</canvas>';
        $this->assertEquals($expected, Tags::canvas('text', ['class' => 'test']));
    }

    public function testCaption()
    {
        $expected = '<caption class="test">text</caption>';
        $this->assertEquals($expected, Tags::caption('text', ['class' => 'test', 'align' => 'left']));
    }

    public function testCite()
    {
        $expected = '<cite class="test">text</cite>';
        $this->assertEquals($expected, Tags::cite('text', ['class' => 'test']));
    }

    public function testCode()
    {
        $expected = '<code class="test">text</code>';
        $this->assertEquals($expected, Tags::code('text', ['class' => 'test']));
    }

    public function testCol()
    {
        $expected = '<col class="test"/>';
        $this->assertEquals($expected, Tags::col(['class' => 'test']));
    }

    public function testColgroup()
    {
        $cols = [Tags::col(['class' => 'test']), Tags::col(['class' => 'test'])];
        $expected = '<colgroup class="test">'.Tags::CRLF.'<col class="test"/>'.Tags::CRLF.'<col class="test"/>'.Tags::CRLF.'</colgroup>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::colgroup($cols, ['class' => 'test']));
    }

    public function testCommand()
    {
        $expected = '<command class="test">text</command>';
        $this->assertEquals($expected, Tags::command('text', ['class' => 'test']));
    }

    public function testDatalist()
    {
        $expected = '<datalist class="test">text</datalist>';
        $this->assertEquals($expected, Tags::datalist('text', ['class' => 'test']));
    }

    public function testDd()
    {
        $expected = '<dd class="test">text</dd>';
        $this->assertEquals($expected, Tags::dd('text', ['class' => 'test']));
    }

    public function testDel()
    {
        $expected = '<del class="test">text</del>';
        $this->assertEquals($expected, Tags::del('text', ['class' => 'test']));
    }

    public function testDetails()
    {
        $expected = '<details class="test">text</details>';
        $this->assertEquals($expected, Tags::details('text', ['class' => 'test']));
    }

    public function testDfn()
    {
        $expected = '<dfn class="test">text</dfn>';
        $this->assertEquals($expected, Tags::dfn('text', ['class' => 'test']));
    }

    public function testDiv()
    {
        $expected = '<div class="test">text</div>';
        $this->assertEquals($expected, Tags::div('text', ['class' => 'test', 'align' => 'left']));
    }

    public function testDl()
    {
        $expected = '<dl class="test">text</dl>';
        $this->assertEquals($expected, Tags::dl('text', ['class' => 'test']));
    }

    public function testDt()
    {
        $expected = '<dt class="test">text</dt>';
        $this->assertEquals($expected, Tags::dt('text', ['class' => 'test']));
    }

    public function testEm()
    {
        $expected = '<em class="test">text</em>';
        $this->assertEquals($expected, Tags::em('text', ['class' => 'test']));
    }

    public function testEmbed()
    {
        $expected = '<embed class="test"/>';
        $this->assertEquals($expected, Tags::embed(['class' => 'test']));
    }

    public function testFieldset()
    {
        $expected = '<fieldset class="test">text</fieldset>';
        $this->assertEquals($expected, Tags::fieldset('text', ['class' => 'test']));
    }

    public function testFigcaption()
    {
        $expected = '<figcaption class="test">text</figcaption>';
        $this->assertEquals($expected, Tags::figcaption('text', ['class' => 'test']));
    }

    public function testFigure()
    {
        $expected = '<figure class="test">text</figure>';
        $this->assertEquals($expected, Tags::figure('text', ['class' => 'test']));
    }

    public function testFooter()
    {
        $expected = '<footer class="test">text</footer>';
        $this->assertEquals($expected, Tags::footer('text', ['class' => 'test']));
    }

    public function testForm()
    {
        $expected = '<form class="test">text</form>';
        $this->assertEquals($expected, Tags::form('text', ['class' => 'test']));
    }

    public function testH1()
    {
        $expected = '<h1 class="test">text</h1>';
        $this->assertEquals($expected, Tags::h1('text', ['class' => 'test']));
    }

    public function testH2()
    {
        $expected = '<h2 class="test">text</h2>';
        $this->assertEquals($expected, Tags::h2('text', ['class' => 'test']));
    }

    public function testH3()
    {
        $expected = '<h3 class="test">text</h3>';
        $this->assertEquals($expected, Tags::h3('text', ['class' => 'test']));
    }

    public function testH4()
    {
        $expected = '<h4 class="test">text</h4>';
        $this->assertEquals($expected, Tags::h4('text', ['class' => 'test']));
    }

    public function testH5()
    {
        $expected = '<h5 class="test">text</h5>';
        $this->assertEquals($expected, Tags::h5('text', ['class' => 'test']));
    }

    public function testH6()
    {
        $expected = '<h6 class="test">text</h6>';
        $this->assertEquals($expected, Tags::h6('text', ['class' => 'test']));
    }

    public function testHead()
    {
        $expected = '<head>'.Tags::CRLF.'<title>test</title>'.Tags::CRLF.'</head>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::head([Tags::title('test')]));
    }

    public function testHeader()
    {
        $expected = '<header class="test">text</header>';
        $this->assertEquals($expected, Tags::header('text', ['class' => 'test']));
    }

    public function testHr()
    {
        $expected = '<hr class="test"/>';
        $this->assertEquals($expected, Tags::hr(['class' => 'test', 'align' => 'center', 'noshade' => 'noshade', 'size' => '1', 'width' => '60%']));
    }

    public function testHtml()
    {
        $expected = '<html xmlns="http://www.w3.org/1999/xhtml">'.Tags::CRLF.'text'.Tags::CRLF.'</html>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::html('text'));
    }

    public function testI()
    {
        $expected = '<i class="test">text</i>';
        $this->assertEquals($expected, Tags::i('text', ['class' => 'test']));
    }

    public function testIframe()
    {
        $expected = '<iframe src="http://msworks.pl/" class="test"></iframe>';
        $this->assertEquals($expected, Tags::iframe(['src' => 'http://msworks.pl/', 'class' => 'test']));
    }

    public function testImg()
    {
        $expected = '<img src="http://msworks.pl/s/p/l/logo.png" class="test" alt=""/>';
        $this->assertEquals($expected, Tags::img('', ['src' => 'http://msworks.pl/s/p/l/logo.png', 'class' => 'test', 'align' => 'left', 'border' => '0', 'hspace' => '0', 'vspace' => '0']));
    }

    public function testInput()
    {
        $expected = '<input class="test"/>';
        $this->assertEquals($expected, Tags::input(['class' => 'test']));
    }

    public function testIns()
    {
        $expected = '<ins class="test">text</ins>';
        $this->assertEquals($expected, Tags::ins('text', ['class' => 'test']));
    }

    public function testKbd()
    {
        $expected = '<kbd class="test">text</kbd>';
        $this->assertEquals($expected, Tags::kbd('text', ['class' => 'test']));
    }

    public function testKeygen()
    {
        $expected = '<keygen class="test"/>';
        $this->assertEquals($expected, Tags::keygen(['class' => 'test']));
    }

    public function testLabel()
    {
        $expected = '<label class="test">text</label>';
        $this->assertEquals($expected, Tags::label('text', ['class' => 'test']));
    }

    public function testLegend()
    {
        $expected = '<legend class="test">text</legend>';
        $this->assertEquals($expected, Tags::legend('text', ['class' => 'test', 'align' => 'left']));
    }

    public function testLi()
    {
        $expected = '<li class="test">text</li>';
        $this->assertEquals($expected, Tags::li('text', ['class' => 'test']));
    }

    public function testLink()
    {
        $expected = '<link class="test"/>';
        $this->assertEquals($expected, Tags::link(['class' => 'test']));
    }

    public function testMap()
    {
        $areaTags = [Tags::area(['class' => 'test']), Tags::area(['class' => 'test'])];
        $expected = '<map class="test">'.Tags::CRLF.'<area class="test"/>'.Tags::CRLF.'<area class="test"/>'.Tags::CRLF.'</map>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::map($areaTags, ['class' => 'test']));
    }

    public function testMark()
    {
        $expected = '<mark class="test">text</mark>';
        $this->assertEquals($expected, Tags::mark('text', ['class' => 'test']));
    }

    public function testMenu()
    {
        $expected = '<menu class="test">text</menu>';
        $this->assertEquals($expected, Tags::menu('text', ['class' => 'test']));
    }

    public function testMeta()
    {
        $expected = '<meta name="test"/>';
        $this->assertEquals($expected, Tags::meta(['name' => 'test']));
    }

    public function testMeter()
    {
        $expected = '<meter class="test" value="">text</meter>';
        $this->assertEquals($expected, Tags::meter('text', ['class' => 'test']));
    }

    public function testNav()
    {
        $expected = '<nav class="test">text</nav>';
        $this->assertEquals($expected, Tags::nav('text', ['class' => 'test']));
    }

    public function testNoscript()
    {
        $expected = '<noscript class="test">text</noscript>';
        $this->assertEquals($expected, Tags::noscript('text', ['class' => 'test']));
    }

    public function testObject()
    {
        $paramTags = [Tags::param(['class' => 'test']), Tags::param(['class' => 'test'])];

        $expected = '<object class="test">'.Tags::CRLF.'<param class="test" name="" value=""/>'.Tags::CRLF.'<param class="test" name="" value=""/>'.Tags::CRLF.'</object>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::object($paramTags, ['class' => 'test']));
    }

    public function testOl()
    {
        $liTags = [Tags::li('text', ['class' => 'test']), Tags::li('text', ['class' => 'test'])];

        $expected = '<ol class="test">'.Tags::CRLF.'<li class="test">text</li>'.Tags::CRLF.'<li class="test">text</li>'.Tags::CRLF.'</ol>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::ol($liTags, ['class' => 'test']));
    }

    public function testOptgroup()
    {
        $optionTags = [Tags::option('text', ['class' => 'test']), Tags::option('text', ['class' => 'test'])];
        $expected = '<optgroup class="test" label="">'.Tags::CRLF.'<option class="test">text</option>'.Tags::CRLF.'<option class="test">text</option>'.Tags::CRLF.'</optgroup>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::optgroup($optionTags, ['class' => 'test']));
    }

    public function testOption()
    {
        $expected = '<option class="test">text</option>';
        $this->assertEquals($expected, Tags::option('text', ['class' => 'test']));
    }

    public function testOutput()
    {
        $expected = '<output class="test">text</output>';
        $this->assertEquals($expected, Tags::output('text', ['class' => 'test']));
    }

    public function testP()
    {
        $expected = '<p class="test">text</p>';
        $this->assertEquals($expected, Tags::p('text', ['class' => 'test']));
    }

    public function testParam()
    {
        $expected = '<param class="test" name="" value=""/>';
        $this->assertEquals($expected, Tags::param(['class' => 'test']));
    }

    public function testPre()
    {
        $expected = '<pre class="test">text</pre>';
        $this->assertEquals($expected, Tags::pre('text', ['class' => 'test', 'width' => '100%']));
    }

    public function testProgress()
    {
        $expected = '<progress class="test">text</progress>';
        $this->assertEquals($expected, Tags::progress('text', ['class' => 'test']));
    }

    public function testQ()
    {
        $expected = '<q class="test">text</q>';
        $this->assertEquals($expected, Tags::q('text', ['class' => 'test']));
    }

    public function testRp()
    {
        $expected = '<rp class="test">text</rp>';
        $this->assertEquals($expected, Tags::rp('text', ['class' => 'test']));
    }

    public function testRt()
    {
        $expected = '<rt class="test">text</rt>';
        $this->assertEquals($expected, Tags::rt('text', ['class' => 'test']));
    }

    public function testRuby()
    {
        $expected = '<ruby class="test">text</ruby>';
        $this->assertEquals($expected, Tags::ruby('text', ['class' => 'test']));
    }

    public function testS()
    {
        $expected = '<s class="test">text</s>';
        $this->assertEquals($expected, Tags::s('text', ['class' => 'test']));
    }

    public function testSamp()
    {
        $expected = '<samp class="test">text</samp>';
        $this->assertEquals($expected, Tags::samp('text', ['class' => 'test']));
    }

    public function testScript()
    {
        $expected = '<script type="text/javascript">console.log("test");</script>';
        $this->assertEquals($expected, Tags::script('console.log("test");', ['language' => 'test']));
    }

    public function testSection()
    {
        $expected = '<section class="test">text</section>';
        $this->assertEquals($expected, Tags::section('text', ['class' => 'test']));
    }

    public function testSelect()
    {
        $optionTags = [Tags::option('text', ['class' => 'test']), Tags::option('text', ['class' => 'test'])];
        $expected = '<select class="test">'.Tags::CRLF.'<option class="test">text</option>'.Tags::CRLF.'<option class="test">text</option>'.Tags::CRLF.'</select>'.Tags::CRLF;
        $this->assertEquals($expected, Tags::select($optionTags, ['class' => 'test']));
    }

    public function testSmall()
    {
        $expected = '<small class="test">text</small>';
        $this->assertEquals($expected, Tags::small('text', ['class' => 'test']));
    }

    public function testSource()
    {
        $expected = '<source class="test"/>';
        $this->assertEquals($expected, Tags::source(['class' => 'test']));
    }

    public function testSpan()
    {
        $expected = '<span class="test">text</span>';
        $this->assertEquals($expected, Tags::span('text', ['class' => 'test']));
    }

    public function testStrong()
    {
        $expected = '<strong class="test">text</strong>';
        $this->assertEquals($expected, Tags::strong('text', ['class' => 'test']));
    }

    public function testStyle()
    {
        $expected = '<style type="text/css">text</style>';
        $this->assertEquals($expected, Tags::style('text', []));
    }

    public function testSub()
    {
        $expected = '<sub class="test">text</sub>';
        $this->assertEquals($expected, Tags::sub('text', ['class' => 'test']));
    }

    public function testSummary()
    {
        $expected = '<summary class="test">text</summary>';
        $this->assertEquals($expected, Tags::summary('text', ['class' => 'test']));
    }

    public function testSup()
    {
        $expected = '<sup class="test">text</sup>';
        $this->assertEquals($expected, Tags::sup('text', ['class' => 'test']));
    }

    public function testTable()
    {
        $expected = '<table class="test" cellpadding="0" cellspacing="0" border="0"/>';
        $this->assertEquals($expected, Tags::table([], ['class' => 'test', 'align' => 'left', 'bgcolor' => '#fff']));
    }

    public function testTbody()
    {
        $expected = '<tbody class="test"/>';
        $this->assertEquals($expected, Tags::tbody([], ['class' => 'test']));
    }

    public function testTd()
    {
        $expected = '<td class="test">text</td>';
        $this->assertEquals($expected, Tags::td('text', ['class' => 'test', 'bgcolor' => '#fff', 'width' => '100', 'height' => '100', 'nowrap' => 'nowrap']));
    }

    public function testTextarea()
    {
        $expected = '<textarea class="test">text</textarea>';
        $this->assertEquals($expected, Tags::textarea('text', ['class' => 'test']));
    }

    public function testTfoot()
    {
        $expected = '<tfoot class="test"/>';
        $this->assertEquals($expected, Tags::tfoot([], ['class' => 'test']));
    }

    public function testTh()
    {
        $expected = '<th class="test">text</th>';
        $this->assertEquals($expected, Tags::th('text', ['class' => 'test', 'bgcolor' => '#fff', 'width' => '100', 'height' => '100', 'nowrap' => 'nowrap']));
    }

    public function testThead()
    {
        $expected = '<thead class="test"/>';
        $this->assertEquals($expected, Tags::thead([], ['class' => 'test']));
    }

    public function testTime()
    {
        $expected = '<time class="test">text</time>';
        $this->assertEquals($expected, Tags::time('text', ['class' => 'test']));
    }

    public function testTitle()
    {
        $expected = '<title class="test">text</title>';
        $this->assertEquals($expected, Tags::title('text', ['class' => 'test']));
    }

    public function testTr()
    {
        $expected = '<tr class="test"/>';
        $this->assertEquals($expected, Tags::tr([], ['class' => 'test']));
    }

    public function testTrack()
    {
        $expected = '<track class="test" src=""/>';
        $this->assertEquals($expected, Tags::track(['class' => 'test']));
    }

    public function testU()
    {
        $expected = '<u class="test">text</u>';
        $this->assertEquals($expected, Tags::u('text', ['class' => 'test']));
    }

    public function testUl()
    {
        $expected = '<ul class="test"/>';
        $this->assertEquals($expected, Tags::ul([], ['class' => 'test']));
    }

    public function testVartag()
    {
        $expected = '<var class="test">text</var>';
        $this->assertEquals($expected, Tags::vartag('text', ['class' => 'test']));
    }

    public function testVideo()
    {
        $expected = '<video class="test">text</video>';
        $this->assertEquals($expected, Tags::video('text', ['class' => 'test']));
    }

    public function testWbr()
    {
        $expected = '<wbr class="test">text</wbr>';
        $this->assertEquals($expected, Tags::wbr('text', ['class' => 'test']));
    }
}
