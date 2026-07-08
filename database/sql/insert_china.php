<?php
require 'C:\xampp\htdocs\mvc-lito\vendor\autoload.php';
$app = require_once 'C:\xampp\htdocs\mvc-lito\bootstrap\app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pais_id = 21;

// ============ DEPARTAMENTOS (provincias, regiones autónomas, municipalidades, SARs) ============
$departamentos = [
    'Anhui', 'Beijing', 'Chongqing', 'Fujian', 'Gansu', 'Guangdong',
    'Guangxi', 'Guizhou', 'Hainan', 'Hebei', 'Heilongjiang', 'Henan',
    'Hong Kong', 'Hubei', 'Hunan', 'Jiangsu', 'Jiangxi', 'Jilin',
    'Liaoning', 'Macau', 'Nei Mongol (Inner Mongolia)', 'Ningxia',
    'Qinghai', 'Shaanxi', 'Shandong', 'Shanghai', 'Shanxi', 'Sichuan',
    'Taiwan', 'Tianjin', 'Xinjiang', 'Xizang (Tibet)', 'Yunnan', 'Zhejiang',
];

DB::statement('SET FOREIGN_KEY_CHECKS=0');
$depto_ids = [];
foreach ($departamentos as $d) {
    DB::table('departamentos')->insert(['pais_id' => $pais_id, 'nombre' => $d]);
    $depto_ids[] = DB::getPdo()->lastInsertId();
}
$depto_map = array_combine($departamentos, $depto_ids);

// ============ PROVINCIAS (prefecturas, ciudades-prefectura, prefecturas autónomas) ============
$provincias = [];

$provincias['Anhui'] = [
    'Hefei', 'Anqing', 'Bengbu', 'Bozhou', 'Chizhou', 'Chuzhou', 'Fuyang',
    'Huaibei', 'Huainan', 'Huangshan', 'Lu\'an', 'Ma\'anshan', 'Suzhou', 'Tongling',
    'Wuhu', 'Xuancheng',
];

$provincias['Beijing'] = ['Beijing'];

$provincias['Chongqing'] = ['Chongqing'];

$provincias['Fujian'] = [
    'Fuzhou', 'Longyan', 'Nanping', 'Ningde', 'Putian', 'Quanzhou', 'Sanming',
    'Xiamen', 'Zhangzhou',
];

$provincias['Gansu'] = [
    'Lanzhou', 'Baiyin', 'Dingxi', 'Gannan', 'Jiayuguan', 'Jinchang',
    'Jiuquan', 'Longnan', 'Linxia', 'Pingliang', 'Qingyang', 'Tianshui',
    'Wuwei', 'Zhangye',
];

$provincias['Guangdong'] = [
    'Guangzhou', 'Chaozhou', 'Dongguan', 'Foshan', 'Heyuan', 'Huizhou',
    'Jiangmen', 'Jieyang', 'Maoming', 'Meizhou', 'Qingyuan', 'Shantou',
    'Shanwei', 'Shaoguan', 'Shenzhen', 'Yangjiang', 'Yunfu', 'Zhanjiang',
    'Zhaoqing', 'Zhuhai', 'Zhongshan',
];

$provincias['Guangxi'] = [
    'Nanning', 'Baise', 'Beihai', 'Chongzuo', 'Fangchenggang', 'Guigang',
    'Guilin', 'Hechi', 'Hezhou', 'Laibin', 'Liuzhou', 'Qinzhou', 'Wuzhou',
    'Yulin',
];

$provincias['Guizhou'] = [
    'Guiyang', 'Anshun', 'Bijie', 'Gui\'an', 'Liupanshui', 'Qiandongnan',
    'Qiannan', 'Qianxinan', 'Tongren', 'Zunyi',
];

$provincias['Hainan'] = [
    'Haikou', 'Baisha', 'Baoting', 'Changjiang', 'Chengmai', 'Danzhou',
    'Dongfang', 'Ledong', 'Lingao', 'Lingshui', 'Qionghai', 'Qiongzhong',
    'Sanya', 'Sansha', 'Tunchang', 'Wanning', 'Wenchang', 'Wuzhishan',
];

$provincias['Hebei'] = [
    'Shijiazhuang', 'Anguo', 'Baoding', 'Botou', 'Cangzhou', 'Chengde',
    'Dingzhou', 'Handan', 'Hengshui', 'Langfang', 'Luanping', 'Qinhuangdao',
    'Renqiu', 'Sanhe', 'Shahe', 'Tangshan', 'Xingtai', 'Xinji', 'Zhangjiakou',
    'Zhuozhou',
];

$provincias['Heilongjiang'] = [
    'Harbin', 'Daqing', 'Daxing\'anling', 'Fujin', 'Hegang', 'Heihe',
    'Jiamusi', 'Jixi', 'Mishan', 'Mudanjiang', 'Qiqihar', 'Qitaihe',
    'Shangzhi', 'Shuangyashan', 'Suifenhe', 'Suihua', 'Yichun', 'Zhaodong',
];

$provincias['Henan'] = [
    'Zhengzhou', 'Anyang', 'Hebi', 'Jiaozuo', 'Kaifeng', 'Luohe', 'Luoyang',
    'Nanyang', 'Pingdingshan', 'Puyang', 'Sanmenxia', 'Shangqiu', 'Xinxiang',
    'Xinyang', 'Xuchang', 'Zhoukou', 'Zhumadian',
];

$provincias['Hong Kong'] = ['Hong Kong'];

$provincias['Hubei'] = [
    'Wuhan', 'Ezhou', 'Enshi', 'Huanggang', 'Huangshi', 'Jingmen',
    'Jingzhou', 'Qianjiang', 'Shiyan', 'Suizhou', 'Tianmen', 'Xiangyang',
    'Xianning', 'Xiantao', 'Xiaogan', 'Yichang',
];

$provincias['Hunan'] = [
    'Changsha', 'Changde', 'Chenzhou', 'Hengyang', 'Huaihua', 'Loudi',
    'Shaoyang', 'Xiangtan', 'Xiangxi', 'Yiyang', 'Yongzhou', 'Yueyang',
    'Zhangjiajie', 'Zhuzhou',
];

$provincias['Jiangsu'] = [
    'Nanjing', 'Changzhou', 'Huai\'an', 'Lianyungang', 'Nantong', 'Suqian',
    'Suzhou', 'Taizhou', 'Wuxi', 'Xuzhou', 'Yancheng', 'Yangzhou', 'Zhenjiang',
];

$provincias['Jiangxi'] = [
    'Nanchang', 'Fuzhou', 'Ganzhou', 'Ji\'an', 'Jingdezhen', 'Jiujiang',
    'Pingxiang', 'Shangrao', 'Xinyu', 'Yichun', 'Yingtan',
];

$provincias['Jilin'] = [
    'Changchun', 'Baicheng', 'Baishan', 'Jilin', 'Liaoyuan', 'Siping',
    'Songyuan', 'Tonghua', 'Yanbian',
];

$provincias['Liaoning'] = [
    'Shenyang', 'Anshan', 'Benxi', 'Chaoyang', 'Dalian', 'Dandong',
    'Fushun', 'Fuxin', 'Huludao', 'Jinzhou', 'Liaoyang', 'Panjin',
    'Tieling', 'Yingkou',
];

$provincias['Macau'] = ['Macau'];

$provincias['Nei Mongol (Inner Mongolia)'] = [
    'Hohhot', 'Alxa', 'Baotou', 'Bayannur', 'Chifeng', 'Erdos (Ordos)',
    'Hinggan', 'Huunbuir', 'Tongliao', 'Ulanqab', 'Wuhai', 'Xilin Gol',
];

$provincias['Ningxia'] = [
    'Yinchuan', 'Guyuan', 'Shizuishan', 'Wuzhong', 'Zhongwei',
];

$provincias['Qinghai'] = [
    'Xining', 'Golog', 'Haibei', 'Haidong', 'Hainan (Qinghai)', 'Haixi',
    'Huangnan', 'Yushu',
];

$provincias['Shaanxi'] = [
    'Xi\'an', 'Ankang', 'Baoji', 'Hanzhong', 'Shangluo', 'Tongchuan',
    'Weinan', 'Xianyang', 'Yan\'an', 'Yulin',
];

$provincias['Shandong'] = [
    'Jinan', 'Binzhou', 'Dezhou', 'Dongying', 'Heze', 'Jining', 'Laiwu',
    'Liaocheng', 'Linyi', 'Qingdao', 'Rizhao', 'Tai\'an', 'Weifang',
    'Weihai', 'Yantai', 'Zaozhuang', 'Zibo',
];

$provincias['Shanghai'] = ['Shanghai'];

$provincias['Shanxi'] = [
    'Taiyuan', 'Changzhi', 'Datong', 'Jincheng', 'Jinzhong', 'Linfen',
    'Lüliang', 'Shuozhou', 'Xinzhou', 'Yangquan', 'Yuncheng',
];

$provincias['Sichuan'] = [
    'Chengdu', 'Aba (Ngawa)', 'Bazhong', 'Dazhou', 'Deyang', 'Ganzi',
    'Guang\'an', 'Guangyuan', 'Leshan', 'Liangshan', 'Luzhou', 'Meishan',
    'Mianyang', 'Nanchong', 'Neijiang', 'Panzhihua', 'Suining', 'Ya\'an',
    'Yibin', 'Zigong', 'Ziyang',
];

$provincias['Taiwan'] = [
    'Taipei', 'Kaohsiung', 'New Taipei', 'Taichung', 'Tainan', 'Taoyuan',
    'Changhua', 'Chiayi', 'Hsinchu', 'Hualien', 'Keelung', 'Kinmen',
    'Lienchiang', 'Miaoli', 'Nantou', 'Penghu', 'Pingtung', 'Taitung',
    'Yilan', 'Yunlin',
];

$provincias['Tianjin'] = ['Tianjin'];

$provincias['Xinjiang'] = [
    'Urumqi', 'Aksu', 'Altay', 'Aral', 'Bayingolin', 'Beitun', 'Bortala',
    'Changji', 'Hami', 'Hotan', 'Ili', 'Karamay', 'Kashgar (Kashi)',
    'Kizilsu', 'Kunyu', 'Shihezi', 'Shuanghe', 'Tacheng', 'Tumxuk',
    'Turpan', 'Wujiaqu', 'Tiemenguan',
];

$provincias['Xizang (Tibet)'] = [
    'Lhasa', 'Chamdo (Qamdo)', 'Lhoka (Shannan)', 'Nagqu', 'Ngari',
    'Nyingchi', 'Shigatse (Xigazê)',
];

$provincias['Yunnan'] = [
    'Kunming', 'Baoshan', 'Chuxiong', 'Dali', 'Dehong', 'Diqing',
    'Honghe', 'Lincang', 'Lijiang', 'Nujiang', 'Puer', 'Qujing',
    'Wenshan', 'Xishuangbanna', 'Yuxi', 'Zhaotong',
];

$provincias['Zhejiang'] = [
    'Hangzhou', 'Huzhou', 'Jiaxing', 'Jinhua', 'Lishui', 'Ningbo',
    'Quzhou', 'Shaoxing', 'Taizhou', 'Wenzhou', 'Zhoushan',
];

// Insert provincias
$prov_ids = [];
$total_prov = 0;
foreach ($provincias as $depto_nombre => $prov_list) {
    $depto_id = $depto_map[$depto_nombre];
    foreach ($prov_list as $prov_nombre) {
        DB::table('provincias')->insert(['departamento_id' => $depto_id, 'nombre' => $prov_nombre]);
        $prov_ids[$depto_nombre][] = DB::getPdo()->lastInsertId();
        $total_prov++;
    }
}

echo "Inserted " . count($departamentos) . " departamentos for China.\n";
echo "Inserted $total_prov provincias for China.\n";

// Build provincia map
$prov_map = [];
foreach ($prov_ids as $depto_nombre => $ids) {
    foreach ($ids as $i => $id) {
        $prov_map[$depto_nombre][$provincias[$depto_nombre][$i]] = $id;
    }
}

// ============ DISTRITOS (county-level divisions) ============
$distritos = [];

// Beijing
$distritos['Beijing'] = [
    'Dongcheng', 'Xicheng', 'Chaoyang', 'Fengtai', 'Shijingshan', 'Haidian',
    'Mentougou', 'Fangshan', 'Tongzhou', 'Shunyi', 'Changping', 'Daxing',
    'Huairou', 'Pinggu', 'Miyun', 'Yanqing',
];

// Chongqing
$distritos['Chongqing'] = [
    'Yuzhong', 'Dadukou', 'Jiangbei', 'Shapingba', 'Jiulongpo', 'Nanan',
    'Beibei', 'Yubei', 'Banan', 'Changshou', 'Jiangjin', 'Hechuan',
    'Yongchuan', 'Nanchuan', 'Bishan', 'Tongliang', 'Tongnan',
    'Dazu', 'Rongchang', 'Kaizhou', 'Liangping', 'Wulong', 'Chengkou',
    'Fengdu', 'Dianjiang', 'Zhongxian', 'Yunyang', 'Fengjie', 'Wushan',
    'Wuxi', 'Shizhu', 'Xiushan', 'Youyang', 'Pengshui', 'Qianjiang',
];

// Shanghai
$distritos['Shanghai'] = [
    'Huangpu', 'Xuhui', 'Changning', 'Jing\'an', 'Putuo', 'Hongkou',
    'Yangpu', 'Minhang', 'Baoshan', 'Jiading', 'Pudong', 'Jinshan',
    'Songjiang', 'Qingpu', 'Fengxian', 'Chongming',
];

// Tianjin
$distritos['Tianjin'] = [
    'Heping', 'Hedong', 'Hexi', 'Nankai', 'Hebei', 'Hongqiao', 'Dongli',
    'Xiqing', 'Jinnan', 'Beichen', 'Baodi', 'Wuqing', 'Jinghai', 'Ninghe',
    'Jizhou', 'Binhai',
];

// Hong Kong
$distritos['Hong Kong'] = [
    'Central and Western', 'Eastern', 'Wan Chai', 'Southern', 'Kowloon City',
    'Kwun Tong', 'Sham Shui Po', 'Wong Tai Sin', 'Yau Tsim Mong', 'Islands',
    'Kwai Tsing', 'North', 'Sai Kung', 'Sha Tin', 'Tai Po', 'Tsuen Wan',
    'Tuen Mun', 'Yuen Long',
];

// Macau
$distritos['Macau'] = [
    'Nossa Senhora de Fátima', 'Santo António', 'São Lázaro', 'Sé',
    'São Lourenço', 'Ilhas',
];

// Guangdong - major cities with districts
$distritos['Guangzhou'] = [
    'Liwan', 'Yuexiu', 'Haizhu', 'Tianhe', 'Baiyun', 'Huangpu', 'Panyu',
    'Huadu', 'Nansha', 'Conghua', 'Zengcheng',
];
$distritos['Shenzhen'] = [
    'Luohu', 'Futian', 'Nanshan', 'Yantian', 'Bao\'an', 'Longgang',
    'Longhua', 'Pingshan', 'Guangming', 'Dapeng',
];
$distritos['Zhuhai'] = ['Xiangzhou', 'Doumen', 'Jinwan'];
$distritos['Shantou'] = ['Longhu', 'Jinping', 'Haojiang', 'Chaoyang', 'Chaonan', 'Chenghai'];
$distritos['Foshan'] = ['Chancheng', 'Nanhai', 'Shunde', 'Gaoming', 'Sanshui'];
$distritos['Dongguan'] = ['Dongguan']; // no county-level divisions
$distritos['Zhongshan'] = ['Zhongshan'];
$distritos['Jiangmen'] = ['Pengjiang', 'Jianghai', 'Xinhui', 'Taishan', 'Kaiping', 'Enping', 'Heshan'];
$distritos['Zhaoqing'] = ['Duanzhou', 'Dinghu', 'Gaoyao', 'Sihui', 'Guangning', 'Huaiji', 'Fengkai', 'Deqing'];
$distritos['Huizhou'] = ['Huicheng', 'Huiyang', 'Boluo', 'Huidong', 'Longmen'];
$distritos['Meizhou'] = ['Meijiang', 'Meixian', 'Xingning', 'Dabu', 'Fengshun', 'Pingyuan', 'Jiaoling'];
$distritos['Shanwei'] = ['Chengqu', 'Lufeng', 'Haifeng', 'Luhe'];
$distritos['Heyuan'] = ['Yuancheng', 'Zijin', 'Longchuan', 'Lianping', 'Heping', 'Dongyuan'];
$distritos['Yangjiang'] = ['Jiangcheng', 'Yangdong', 'Yangchun', 'Yangxi'];
$distritos['Qingyuan'] = ['Qingcheng', 'Qingxin', 'Yingde', 'Lianzhou', 'Fogang', 'Yangshan', 'Lianshan', 'Liannan'];
$distritos['Chaozhou'] = ['Xiangqiao', 'Chao\'an', 'Raoping'];
$distritos['Jieyang'] = ['Rongcheng', 'Jiedong', 'Puning', 'Jiexi', 'Huilai'];
$distritos['Yunfu'] = ['Yuncheng', 'Yun\'an', 'Luoding', 'Xinxing', 'Yunan'];
$distritos['Maoming'] = ['Maonan', 'Dianbai', 'Gaozhou', 'Huazhou', 'Xinyi'];
$distritos['Zhanjiang'] = ['Chikan', 'Xiashan', 'Potou', 'Mazhang', 'Wuchuan', 'Lianjiang', 'Leizhou', 'Suixi', 'Xuwen'];
$distritos['Shaoguan'] = ['Wujiang', 'Zhenjiang', 'Qujiang', 'Lechang', 'Nanxiong', 'Shixing', 'Renhua', 'Wengyuan', 'Xinfeng', 'Ruyuan'];

// Jiangsu
$distritos['Nanjing'] = ['Xuanwu', 'Qinhuai', 'Jianye', 'Gulou', 'Pukou', 'Qixia', 'Yuhuatai', 'Jiangning', 'Lishui', 'Gaochun'];
$distritos['Suzhou'] = ['Gusu', 'Huqiu', 'Wuzhong', 'Xiangcheng', 'Wujiang', 'Changshu', 'Zhangjiagang', 'Kunshan', 'Taicang'];
$distritos['Wuxi'] = ['Xishan', 'Huishan', 'Binhu', 'Liangxi', 'Xinwu', 'Jiangyin', 'Yixing'];
$distritos['Changzhou'] = ['Tianning', 'Zhonglou', 'Qishuyan', 'Jintan', 'Wujin', 'Xinbei', 'Liyang'];
$distritos['Nantong'] = ['Chongchuan', 'Tongzhou', 'Haimen', 'Qidong', 'Rugao', 'Rudong', 'Hai\'an'];
$distritos['Yangzhou'] = ['Guangling', 'Hanjiang', 'Jiangdu', 'Yizheng', 'Gaoyou', 'Baoying'];
$distritos['Zhenjiang'] = ['Jingkou', 'Runzhou', 'Dantu', 'Danyang', 'Yangzhong', 'Jurong'];
$distritos['Taizhou'] = ['Hailing', 'Gaogang', 'Jiangyan', 'Xinghua', 'Jingjiang', 'Taixing'];
$distritos['Xuzhou'] = ['Yunlong', 'Gulou', 'Jiawang', 'Quanshan', 'Tongshan', 'Xinyi', 'Pizhou', 'Fengxian', 'Peixian', 'Suining'];
$distritos['Yancheng'] = ['Tinghu', 'Yandu', 'Dafeng', 'Dongtai', 'Xiangshui', 'Binhai', 'Funing', 'Sheyang', 'Jianhu'];
$distritos['Huai\'an'] = ['Qingjiangpu', 'Huai\'an', 'Huaiyin', 'Hongze', 'Lianshui', 'Xuyi', 'Jinhu'];
$distritos['Lianyungang'] = ['Lianyun', 'Haizhou', 'Ganyu', 'Donghai', 'Guanyun', 'Guannan'];
$distritos['Suqian'] = ['Sucheng', 'Suyu', 'Shuyang', 'Siyang', 'Sihong'];

// Zhejiang
$distritos['Hangzhou'] = ['Shangcheng', 'Xiacheng', 'Jianggan', 'Gongshu', 'Xihu', 'Binjiang', 'Xiaoshan', 'Yuhang', 'Fuyang', 'Lin\'an', 'Tonglu', 'Chun\'an', 'Jiande'];
$distritos['Ningbo'] = ['Haishu', 'Jiangbei', 'Beilun', 'Zhenhai', 'Yinzhou', 'Fenghua', 'Yuyao', 'Cixi', 'Ninghai', 'Xiangshan'];
$distritos['Wenzhou'] = ['Lucheng', 'Longwan', 'Ouhai', 'Dongtou', 'Rui\'an', 'Yueqing', 'Yongjia', 'Pingyang', 'Cangnan', 'Wencheng', 'Taishun'];
$distritos['Jiaxing'] = ['Nanhu', 'Xiuzhou', 'Pinghu', 'Haining', 'Tongxiang', 'Jiashan', 'Haiyan'];
$distritos['Huzhou'] = ['Wuxing', 'Nanxun', 'Deqing', 'Changxing', 'Anji'];
$distritos['Shaoxing'] = ['Yuecheng', 'Keqiao', 'Shangyu', 'Zhuji', 'Shengzhou', 'Xinchang'];
$distritos['Jinhua'] = ['Wucheng', 'Jindong', 'Lanxi', 'Yongkang', 'Yiwu', 'Dongyang', 'Wuyi', 'Pujiang', 'Pan\'an'];
$distritos['Quzhou'] = ['Kecheng', 'Qujiang', 'Jiangshan', 'Changshan', 'Kaihua', 'Longyou'];
$distritos['Zhoushan'] = ['Dinghai', 'Putuo', 'Daishan', 'Shengsi'];
$distritos['Taizhou'] = ['Jiaojiang', 'Huangyan', 'Luqiao', 'Linhai', 'Sanmen', 'Tiantai', 'Xianju', 'Wenling', 'Yuhuan'];
$distritos['Lishui'] = ['Liandu', 'Longquan', 'Qingtian', 'Jinyun', 'Suichang', 'Songyang', 'Yunhe', 'Jingning', 'Qingyuan'];

// Sichuan
$distritos['Chengdu'] = ['Jinjiang', 'Qingyang', 'Jinniu', 'Wuhou', 'Chenghua', 'Longquanyi', 'Qingbaijiang', 'Xindu', 'Wenjiang', 'Jintang', 'Shuangliu', 'Pixian', 'Dayi', 'Pujiang', 'Xinjin', 'Dujiangyan', 'Pengzhou', 'Qionglai', 'Chongzhou', 'Jianyang'];
$distritos['Mianyang'] = ['Fucheng', 'Youxian', 'Anzhou', 'Jiangyou', 'Santai', 'Yanting', 'Zitong', 'Pingwu', 'Beichuan'];
$distritos['Deyang'] = ['Jingyang', 'Luojiang', 'Guanghan', 'Shifang', 'Mianzhu', 'Zhongjiang'];
$distritos['Yibin'] = ['Cuiping', 'Nanxi', 'Xuzhou', 'Jiang\'an', 'Changning', 'Gao', 'Gong', 'Junlian', 'Xingwen', 'Pingshan', 'Yibin'];
$distritos['Nanchong'] = ['Shunqing', 'Gaoping', 'Jialing', 'Langzhong', 'Nanbu', 'Yingshan', 'Peng\'an', 'Yilong', 'Xichong'];
$distritos['Luzhou'] = ['Jiangyang', 'Naxi', 'Longmatan', 'Luxian', 'Hejiang', 'Xuyong', 'Gulin'];

// Shandong
$distritos['Jinan'] = ['Lixia', 'Shizhong', 'Huaiyin', 'Tianqiao', 'Licheng', 'Changqing', 'Zhangqiu', 'Jiyang', 'Laiwu', 'Gangcheng', 'Pingyin', 'Shanghe'];
$distritos['Qingdao'] = ['Shinan', 'Shibei', 'Huangdao', 'Laoshan', 'Licang', 'Chengyang', 'Jimo', 'Jiaozhou', 'Pingdu', 'Laixi'];
$distritos['Yantai'] = ['Zhifu', 'Fushan', 'Muping', 'Laishan', 'Penglai', 'Longkou', 'Zhaoyuan', 'Laiyang', 'Haiyang', 'Qixia'];
$distritos['Weifang'] = ['Weicheng', 'Hanting', 'Fangzi', 'Kuiwen', 'Shouguang', 'Zhucheng', 'Anqiu', 'Gaomi', 'Changyi', 'Linqu', 'Changle'];
$distritos['Linyi'] = ['Lanshan', 'Luozhuang', 'Hedong', 'Yinan', 'Tancheng', 'Yishui', 'Cangshan', 'Feixian', 'Pingyi', 'Junan', 'Mengyin', 'Linshu'];
$distritos['Zibo'] = ['Zichuan', 'Zhangdian', 'Boshan', 'Linzi', 'Zhoucun', 'Huantai', 'Gaoqing', 'Yiyuan'];
$distritos['Jining'] = ['Rencheng', 'Yanzhou', 'Qufu', 'Zoucheng', 'Weishan', 'Yutai', 'Jinxiang', 'Jiaxiang', 'Wenshang', 'Sishui', 'Liangshan'];
$distritos['Tai\'an'] = ['Taishan', 'Daiyue', 'Xintai', 'Feicheng', 'Ningyang', 'Dongping'];
$distritos['Weihai'] = ['Huancui', 'Wendeng', 'Rongcheng', 'Rushan'];
$distritos['Rizhao'] = ['Donggang', 'Lanshan', 'Wulian', 'Juxian'];
$distritos['Dezhou'] = ['Decheng', 'Lingcheng', 'Laoling', 'Yucheng', 'Ningjin', 'Qingyun', 'Linyi', 'Qihe', 'Pingyuan', 'Xiajin', 'Wucheng'];
$distritos['Liaocheng'] = ['Dongchangfu', 'Linqing', 'Yanggu', 'Dong\'e', 'Chiping', 'Shenxian', 'Guanxian', 'Gaotang'];
$distritos['Binzhou'] = ['Bincheng', 'Zhanhua', 'Zouping', 'Huimin', 'Yangxin', 'Wudi', 'Boxing'];
$distritos['Heze'] = ['Mudan', 'Dingtao', 'Cao', 'Shanxian', 'Chengwu', 'Juye', 'Yuncheng', 'Juancheng', 'Dongming'];
$distritos['Dongying'] = ['Dongying', 'Hekou', 'Kenli', 'Lijin', 'Guangrao'];

// Hubei
$distritos['Wuhan'] = ['Jiang\'an', 'Jianghan', 'Qiaokou', 'Hanyang', 'Wuchang', 'Qingshan', 'Hongshan', 'Dongxihu', 'Hannan', 'Caidian', 'Jiangxia', 'Huangpi', 'Xinzhou'];
$distritos['Xiangyang'] = ['Xiangcheng', 'Fancheng', 'Xiangzhou', 'Laohekou', 'Zaoyang', 'Yicheng', 'Nanzhang', 'Gucheng', 'Baokang'];
$distritos['Yichang'] = ['Xiling', 'Wujiagang', 'Dianjun', 'Xiaoting', 'Yiling', 'Yidu', 'Dangyang', 'Zhijiang', 'Yuan\'an', 'Xingshan', 'Zigui', 'Changyang', 'Wufeng'];
$distritos['Jingzhou'] = ['Shashi', 'Jingzhou', 'Jingzhou 2', 'Shishou', 'Honghu', 'Songzi', 'Gongan', 'Jianli', 'Jiangling'];
$distritos['Huanggang'] = ['Huangzhou', 'Macheng', 'Wuxue', 'Hong\'an', 'Luotian', 'Yingshan', 'Xishui', 'Qichun', 'Huangmei', 'Tuanfeng'];
$distritos['Shiyan'] = ['Maojian', 'Zhangwan', 'Yunyang', 'Danjiangkou', 'Yunxi', 'Zhushan', 'Zhuxi', 'Fangxian'];
$distritos['Jingmen'] = ['Dongbao', 'Duodao', 'Zhongxiang', 'Jingshan', 'Shayang'];
$distritos['Ezhou'] = ['Echeng', 'Huarong', 'Liangzihu'];
$distritos['Xiaogan'] = ['Xiaonan', 'Yingcheng', 'Anlu', 'Hanchuan', 'Xiaochang', 'Dawu', 'Yunmeng'];
$distritos['Xianning'] = ['Xian\'an', 'Chibi', 'Tongshan', 'Tongcheng', 'Chongyang', 'Jiayu'];
$distritos['Huangshi'] = ['Huangshigang', 'Xisaishan', 'Xialu', 'Tieshan', 'Daye', 'Yangxin'];
$distritos['Enshi'] = ['Enshi', 'Lichuan', 'Jianshi', 'Badong', 'Xianfeng', 'Xuan\'en', 'Laifeng', 'Hefeng'];
$distritos['Suizhou'] = ['Zengdu', 'Guangshui', 'Suixian'];

// Hunan
$distritos['Changsha'] = ['Furong', 'Tianxin', 'Yuelu', 'Kaifu', 'Yuhua', 'Wangcheng', 'Liuyang', 'Ningxiang', 'Changsha'];
$distritos['Zhuzhou'] = ['Hetang', 'Lusong', 'Shifeng', 'Tianyuan', 'Lukou', 'Liling', 'Yanling', 'Chaling', 'Youxian'];
$distritos['Xiangtan'] = ['Yuhu', 'Yuetang', 'Xiangtan', 'Shaoshan', 'Xiangxiang'];
$distritos['Hengyang'] = ['Zhengxiang', 'Zhuhui', 'Yanfeng', 'Shigu', 'Nanyue', 'Changning', 'Leiyang', 'Hengyang', 'Hengnan', 'Hengshan', 'Hengdong', 'Qidong'];
$distritos['Yueyang'] = ['Yueyanglou', 'Yunxi', 'Junshan', 'Miluo', 'Linxiang', 'Yueyang', 'Huaron', 'Xiangyin', 'Pingjiang'];
$distritos['Changde'] = ['Wuling', 'Dingcheng', 'Jinshi', 'Anxiang', 'Hanshou', 'Li', 'Linli', 'Taoyuan', 'Shimen'];
$distritos['Shaoyang'] = ['Shuangqing', 'Daxiang', 'Beita', 'Wugang', 'Shaodong', 'Xinshao', 'Shaoyang', 'Longhui', 'Dongkou', 'Suining', 'Xinning', 'Chengbu'];
$distritos['Chenzhou'] = ['Beihu', 'Suxian', 'Zixing', 'Guiyang', 'Yizhang', 'Yongxing', 'Jiahe', 'Linwu', 'Rucheng', 'Guidong', 'Anren'];
$distritos['Yongzhou'] = ['Lengshuitan', 'Lingling', 'Qiyang', 'Dong\'an', 'Shuangpai', 'Daoxian', 'Jiangyong', 'Ningyuan', 'Xintian', 'Jianghua', 'Lanshan'];
$distritos['Huaihua'] = ['Hexheng', 'Hongjiang', 'Zhongfang', 'Yuanling', 'Chenxi', 'Xupu', 'Huitong', 'Mayang', 'Xinhuang', 'Zhijiang', 'Jingzhou', 'Tongdao'];
$distritos['Loudi'] = ['Louxing', 'Lengshuijiang', 'Lianyuan', 'Shuangfeng', 'Xinhua'];
$distritos['Yiyang'] = ['Ziyang', 'Heshan', 'Yuanjiang', 'Nanxian', 'Taojiang', 'Anhua'];
$distritos['Zhangjiajie'] = ['Yongding', 'Wulingyuan', 'Cili', 'Sangzhi'];
$distritos['Xiangxi'] = ['Jishou', 'Luxi', 'Fenghuang', 'Huayuan', 'Baojing', 'Guzhang', 'Yongshun', 'Longshan'];

// Henan
$distritos['Zhengzhou'] = ['Zhongyuan', 'Erqi', 'Guancheng', 'Jinshui', 'Shangjie', 'Huiji', 'Zhongmu', 'Gongyi', 'Xingyang', 'Xinmi', 'Xinzheng', 'Dengfeng'];
$distritos['Luoyang'] = ['Laocheng', 'Xigong', 'Chanhe', 'Jianxi', 'Luolong', 'Yanshi', 'Mengjin', 'Xin\'an', 'Luanchuan', 'Songxian', 'Ruyang', 'Yiyang', 'Luoning', 'Yichuan'];
$distritos['Kaifeng'] = ['Longting', 'Shunhe', 'Gulou', 'Yuwangtai', 'Xiangfu', 'Qixian', 'Tongxu', 'Weishi', 'Lankao'];
$distritos['Nanyang'] = ['Wancheng', 'Wolong', 'Dengzhou', 'Nanzhao', 'Fangcheng', 'Xixia', 'Zhenping', 'Neixiang', 'Xichuan', 'Sheqi', 'Tanghe', 'Xinye', 'Tongbai'];
$distritos['Xuchang'] = ['Weidu', 'Jian\'an', 'Yuzhou', 'Changge', 'Yanling', 'Xiangcheng'];
$distritos['Xinxiang'] = ['Hongqi', 'Weibin', 'Fengquan', 'Muye', 'Weihui', 'Huixian', 'Xinxiang', 'Huojia', 'Yuanyang', 'Yanjin', 'Fengqiu'];
$distritos['Anyang'] = ['Beiguan', 'Wenfeng', 'Yindu', 'Long\'an', 'Linzhou', 'Anyang', 'Tangyin', 'Hua', 'Neihuang'];
$distritos['Shangqiu'] = ['Liangyuan', 'Suiyang', 'Yongcheng', 'Yucheng', 'Minquan', 'Ningling', 'Zhecheng', 'Sui', 'Xiayi'];
$distritos['Zhoukou'] = ['Chuanhui', 'Xiangcheng', 'Fugou', 'Xihua', 'Shangshui', 'Shenqiu', 'Dancheng', 'Huaiyang', 'Taikang', 'Luyi'];
$distritos['Pingdingshan'] = ['Xinhua', 'Weidong', 'Zhanhe', 'Shilong', 'Ruzhou', 'Wugang', 'Baofeng', 'Yexian', 'Lushan', 'Jiaxian'];
$distritos['Jiaozuo'] = ['Jiefang', 'Shanyang', 'Zhongzhan', 'Macun', 'Qinyang', 'Mengzhou', 'Xiuwu', 'Bo\'ai', 'Wuzhi', 'Wenxian'];
$distritos['Zhumadian'] = ['Yicheng', 'Runan', 'Pingyu', 'Xincai', 'Shangcai', 'Xiping', 'Suiping', 'Zhengyang', 'Queshan', 'Biyang'];
$distritos['Xinyang'] = ['Shihe', 'Pingqiao', 'Huangchuan', 'Xi', 'Gushi', 'Shangcheng', 'Luoshan', 'Guangshan', 'Huaibin', 'Xinxian'];
$distritos['Puyang'] = ['Hualong', 'Qingfeng', 'Nanle', 'Fanxian', 'Taiqian', 'Puyang'];
$distritos['Sanmenxia'] = ['Hubin', 'Shanzhou', 'Yima', 'Lingbao', 'Mianchi', 'Lushi'];
$distritos['Luohe'] = ['Yuanhui', 'Yancheng', 'Shaoling', 'Wuyang', 'Linying'];
$distritos['Hebi'] = ['Heshan', 'Shancheng', 'Qibin', 'Xunxian', 'Qi'];

// Fujian
$distritos['Fuzhou'] = ['Gulou', 'Taijiang', 'Cangshan', 'Mawei', 'Jin\'an', 'Changle', 'Fuqing', 'Minhou', 'Lianjiang', 'Luoyuan', 'Minqing', 'Yongtai', 'Pingtan'];
$distritos['Xiamen'] = ['Siming', 'Haicang', 'Jimei', 'Tong\'an', 'Xiang\'an'];
$distritos['Quanzhou'] = ['Licheng', 'Fengze', 'Luojiang', 'Quangang', 'Shishi', 'Jinjiang', 'Nan\'an', 'Hui\'an', 'Anxi', 'Yongchun', 'Dehua', 'Kinmen'];
$distritos['Zhangzhou'] = ['Xiangcheng', 'Longwen', 'Longhai', 'Yunxiao', 'Zhangpu', 'Zhao\'an', 'Changtai', 'Dongshan', 'Nanjing', 'Pinghe', 'Hua\'an'];
$distritos['Nanping'] = ['Jianyang', 'Yanping', 'Shaowu', 'Wuyishan', 'Jian\'ou', 'Shunchang', 'Pucheng', 'Guangze', 'Songxi', 'Zhenghe'];
$distritos['Longyan'] = ['Xinluo', 'Yongding', 'Zhangping', 'Changting', 'Shanghang', 'Wuping', 'Liancheng'];
$distritos['Sanming'] = ['Meilie', 'Sanyuan', 'Yong\'an', 'Mingxi', 'Qingliu', 'Ninghua', 'Datian', 'Youxi', 'Shaxian', 'Jiangle', 'Taining', 'Jianning'];
$distritos['Putian'] = ['Chengxiang', 'Hanjiang', 'Licheng', 'Xiuyu', 'Xianyou'];
$distritos['Ningde'] = ['Jiaocheng', 'Fu\'an', 'Fuding', 'Xiapu', 'Gutian', 'Pingnan', 'Shouning', 'Zhouning', 'Zherong'];

// Anhui
$distritos['Hefei'] = ['Yaohai', 'Luyang', 'Shushan', 'Baohe', 'Chaohu', 'Changfeng', 'Feidong', 'Feixi', 'Lujiang'];
$distritos['Wuhu'] = ['Jinghu', 'Yijiang', 'Jiujiang', 'Sanshan', 'Wanzhi', 'Wuwei', 'Fanchang', 'Nanling', 'Fanchang'];
$distritos['Bengbu'] = ['Longzihu', 'Bangshan', 'Yuhui', 'Huaishang', 'Huaiyuan', 'Wuhe', 'Gu\'zhen'];
$distritos['Huainan'] = ['Datong', 'Tianjia\'an', 'Xiejiaji', 'Bagongshan', 'Panji', 'Fengtai', 'Shou'];
$distritos['Ma\'anshan'] = ['Huashan', 'Yushan', 'Bowang', 'Dangtu', 'Hanshan', 'He'];
$distritos['Huaibei'] = ['Duji', 'Xiangshan', 'Lieshan', 'Suixi'];
$distritos['Tongling'] = ['Tongguanshan', 'Jiaoqu', 'Yian', 'Zongyang'];
$distritos['Anqing'] = ['Yingjiang', 'Daguan', 'Yixiu', 'Tongcheng', 'Huaining', 'Qianshan', 'Taihu', 'Susong', 'Wangjiang', 'Yuexi'];
$distritos['Huangshan'] = ['Tunxi', 'Huangshan', 'Huizhou', 'Shexian', 'Xiuming', 'Yi', 'Qimen'];
$distritos['Chuzhou'] = ['Langya', 'Nanqiao', 'Tianchang', 'Mingguang', 'Lai\'an', 'Quanjiao', 'Dingyuan', 'Fengyang'];
$distritos['Fuyang'] = ['Yingzhou', 'Yingdong', 'Yingquan', 'Jieshou', 'Linquan', 'Taihe', 'Funan', 'Yingshang'];
$distritos['Suzhou'] = ['Yongqiao', 'Dangshan', 'Xiao', 'Lingbi', 'Si'];
$distritos['Lu\'an'] = ['Jin\'an', 'Yu\'an', 'Yeji', 'Huoqiu', 'Shucheng', 'Jinzhai', 'Huoshan'];
$distritos['Bozhou'] = ['Qiaocheng', 'Guoyang', 'Mengcheng', 'Lixin'];
$distritos['Chizhou'] = ['Guichi', 'Dongzhi', 'Shitai', 'Qingyang'];
$distritos['Xuancheng'] = ['Xuanzhou', 'Ningguo', 'Langxi', 'Guangde', 'Jing', 'Jingde', 'Jixi'];

// Jiangxi
$distritos['Nanchang'] = ['Donghu', 'Xihu', 'Qingyunpu', 'Wanli', 'Qingshanhu', 'Xinjian', 'Nanchang', 'Anyi', 'Jinxian'];
$distritos['Jiujiang'] = ['Xunyang', 'Lushan', 'Chaisang', 'Ruichang', 'Gongqingcheng', 'Lushan', 'Wuning', 'Xiushui', 'Yongxiu', 'De\'an', 'Duchang', 'Hukou', 'Pengze'];
$distritos['Jingdezhen'] = ['Changjiang', 'Zhushan', 'Leping', 'Fuliang'];
$distritos['Pingxiang'] = ['Anyuan', 'Xiangdong', 'Shangli', 'Luxi', 'Lianhua'];
$distritos['Xinyu'] = ['Yushui', 'Fenyi'];
$distritos['Yingtan'] = ['Yuehu', 'Yujiang', 'Guixi'];
$distritos['Ganzhou'] = ['Zhanggong', 'Nankang', 'Ganxian', 'Ruijin', 'Xinfeng', 'Dayu', 'Shangyou', 'Chongyi', 'Anyuan', 'Longnan', 'Dingnan', 'Quannan', 'Ningdu', 'Yudu', 'Xingguo', 'Huichang', 'Xunwu', 'Shicheng'];
$distritos['Ji\'an'] = ['Jizhou', 'Qingyuan', 'Jinggangshan', 'Ji\'an', 'Ji\'shui', 'Xiajiang', 'Xingan', 'Yongfeng', 'Taihe', 'Wan\'an', 'Suichuan', 'Anfu', 'Yongxin'];
$distritos['Yichun'] = ['Yuanzhou', 'Zhangshu', 'Fengcheng', 'Gao\'an', 'Fengxin', 'Wanzai', 'Shanggao', 'Yifeng', 'Jing\'an', 'Tonggu'];
$distritos['Fuzhou'] = ['Linchuan', 'Dongxiang', 'Nancheng', 'Nanfeng', 'Chongren', 'Le\'an', 'Jinxi', 'Zixi', 'Guangchang'];
$distritos['Shangrao'] = ['Xinzhou', 'Guangfeng', 'Guangxin', 'Dexing', 'Wuyuan', 'Yushan', 'Qianshan', 'Hengfeng', 'Yiyang', 'Poyang', 'Wannian', 'Yugan'];

// Yunnan
$distritos['Kunming'] = ['Wuhua', 'Panlong', 'Guandu', 'Xishan', 'Dongchuan', 'Chenggong', 'Jinning', 'Anning', 'Fumin', 'Yiliang', 'Songming', 'Shilin', 'Luquan', 'Xundian'];
$distritos['Qujing'] = ['Qilin', 'Zhanyi', 'Malong', 'Xuanwei', 'Fuyuan', 'Luoping', 'Shizong', 'Luliang', 'Huize'];
$distritos['Yuxi'] = ['Hongta', 'Jiangchuan', 'Tonghai', 'Huaning', 'Yimen', 'Eshan', 'Xinping', 'Yuanjiang'];
$distritos['Baoshan'] = ['Longyang', 'Tengchong', 'Shidian', 'Longling', 'Changning'];
$distritos['Zhaotong'] = ['Zhaoyang', 'Ludian', 'Qiaojia', 'Yanjing', 'Daguan', 'Yongshan', 'Suijiang', 'Zhenxiong', 'Yiliang', 'Weixin'];
$distritos['Lijiang'] = ['Gucheng', 'Yulong', 'Yongsheng', 'Huaping', 'Ninglang'];
$distritos['Puer'] = ['Simao', 'Ning\'er', 'Mojiang', 'Jingdong', 'Jinggu', 'Zhenyuan', 'Jiangcheng', 'Menglian', 'Lancang', 'Ximeng'];
$distritos['Lincang'] = ['Linxiang', 'Fengqing', 'Yun', 'Yongde', 'Zhenkang', 'Shuangjiang', 'Gengma', 'Cangyuan'];
$distritos['Chuxiong'] = ['Chuxiong', 'Shuangbai', 'Mouding', 'Nanhua', 'Yao\'an', 'Dayao', 'Yongren', 'Yuanmou', 'Wuding', 'Lufeng'];
$distritos['Honghe'] = ['Mengzi', 'Gejiu', 'Kaiyuan', 'Mile', 'Honghe', 'Shiping', 'Jianshui', 'Luxi', 'Yuanyang', 'Honghe', 'Jinping', 'Hekou', 'Pingbian'];
$distritos['Wenshan'] = ['Wenshan', 'Yanshan', 'Xichou', 'Malipo', 'Maguan', 'Qiubei', 'Guangnan', 'Funing'];
$distritos['Xishuangbanna'] = ['Jinghong', 'Menghai', 'Mengla'];
$distritos['Dali'] = ['Dali', 'Xiangyun', 'Binchuan', 'Midu', 'Yongping', 'Yunlong', 'Eryuan', 'Jianchuan', 'Heqing', 'Nanjian', 'Weishan', 'Yangbi'];
$distritos['Dehong'] = ['Ruili', 'Mangshi', 'Lianghe', 'Yingjiang', 'Longchuan'];
$distritos['Nujiang'] = ['Lushui', 'Fugong', 'Gongshan', 'Lanping'];
$distritos['Diqing'] = ['Shangri-La', 'Deqin', 'Weixi'];

// Guizhou
$distritos['Guiyang'] = ['Nanming', 'Yunyan', 'Huaxi', 'Wudang', 'Baiyun', 'Guanshanhu', 'Qingzhen', 'Kaiyang', 'Xifeng', 'Xiuwen'];
$distritos['Zunyi'] = ['Honghuagang', 'Huichuan', 'Bozhou', 'Chishui', 'Renhuai', 'Tongzi', 'Suiyang', 'Zheng\'an', 'Daozhen', 'Wuchuan', 'Fenggang', 'Meitan', 'Yuqing', 'Xishui'];
$distritos['Anshun'] = ['Xixiu', 'Pingba', 'Puding', 'Zhenning', 'Guanling', 'Ziyun'];
$distritos['Tongren'] = ['Bijiang', 'Wanshan', 'Jiangkou', 'Yuping', 'Shiqian', 'Sinan', 'Yinjiang', 'Dejiang', 'Yanhe', 'Songtao'];
$distritos['Bijie'] = ['Qixingguang', 'Dafang', 'Qianxi', 'Jinsha', 'Zhijin', 'Nayong', 'Hezhang', 'Weining'];
$distritos['Liupanshui'] = ['Zhongshan', 'Panzhou', 'Shuicheng', 'Liuzhi'];
$distritos['Qiannan'] = ['Duyun', 'Fuquan', 'Libo', 'Guiding', 'Weng\'an', 'Dushan', 'Pingtang', 'Luodian', 'Changshun', 'Longli', 'Huishui', 'Sandu'];
$distritos['Qiandongnan'] = ['Kaili', 'Huangping', 'Shibing', 'Sansui', 'Zhenyuan', 'Cengong', 'Tianzhu', 'Jinping', 'Jianhe', 'Taijiang', 'Liping', 'Rongjiang', 'Congjiang', 'Leishan', 'Majiang', 'Danzhai'];
$distritos['Qianxinan'] = ['Xingyi', 'Xingren', 'Pu\'an', 'Qinglong', 'Zhenfeng', 'Wangmo', 'Ccheng', 'Anlong'];

// Liaoning
$distritos['Shenyang'] = ['Heping', 'Shenhe', 'Dadong', 'Huanggu', 'Tiexi', 'Sujiatun', 'Hunnan', 'Shenbei', 'Yuhong', 'Liaozhong', 'Kangping', 'Faku', 'Xinmin'];
$distritos['Dalian'] = ['Zhongshan', 'Xigang', 'Shahekou', 'Ganjingzi', 'Lushunkou', 'Jinzhou', 'Pulandian', 'Wafangdian', 'Zhuanghe', 'Changhai'];
$distritos['Anshan'] = ['Tiedong', 'Tiexi', 'Lishan', 'Qianshan', 'Haicheng', 'Tai\'an', 'Xiuyan'];
$distritos['Fushun'] = ['Xinfu', 'Wanghua', 'Dongzhou', 'Shuncheng', 'Fushun', 'Qingyuan', 'Xinbin'];
$distritos['Benxi'] = ['Pingshan', 'Xihu', 'Mingshan', 'Nanfen', 'Benxi', 'Huanren'];
$distritos['Dandong'] = ['Zhenxing', 'Yuanbao', 'Zhen\'an', 'Fengcheng', 'Donggang', 'Kuandian'];
$distritos['Jinzhou'] = ['Taihe', 'Guta', 'Linghe', 'Linghai', 'Beizhen', 'Heishan', 'Yi'];
$distritos['Yingkou'] = ['Zhanqian', 'Xishi', 'Bayuquan', 'Laobian', 'Gaizhou', 'Dashiqiao'];
$distritos['Fuxin'] = ['Haizhou', 'Xinqiu', 'Taiping', 'Qinghemen', 'Xihe', 'Zhangwu', 'Fuxin'];
$distritos['Liaoyang'] = ['Baita', 'Wensheng', 'Hongwei', 'Gongchangling', 'Taizihe', 'Dengta', 'Liaoyang'];
$distritos['Panjin'] = ['Shuangtaizi', 'Xinglongtai', 'Dawa', 'Panshan'];
$distritos['Tieling'] = ['Yinzhou', 'Qinghe', 'Diaobingshan', 'Kaiyuan', 'Tieling', 'Xifeng', 'Changtu'];
$distritos['Chaoyang'] = ['Shuangta', 'Longcheng', 'Beipiao', 'Lingyuan', 'Chaoyang', 'Jianping', 'Harqin'];
$distritos['Huludao'] = ['Longgang', 'Lianshan', 'Nanpiao', 'Xingcheng', 'Suizhong', 'Jianchang'];

// Jilin
$distritos['Changchun'] = ['Chaoyang', 'Nanguan', 'Kuancheng', 'Erdao', 'Lvyuan', 'Shuangyang', 'Jiutai', 'Yushu', 'Dehui', 'Nong\'an', 'Gongzhuling'];
$distritos['Jilin'] = ['Changyi', 'Longtan', 'Chuanying', 'Fengman', 'Huadian', 'Jiaohe', 'Shulan', 'Panshi', 'Yongji'];
$distritos['Siping'] = ['Tiexi', 'Tiedong', 'Shuangliao', 'Lishu', 'Yitong', 'Gongzhuling'];
$distritos['Liaoyuan'] = ['Longshan', 'Xi\'an', 'Dongfeng', 'Dongliao'];
$distritos['Tonghua'] = ['Dongchang', 'Erdaojiang', 'Meihekou', 'Ji\'an', 'Tonghua', 'Huinan', 'Liuhe'];
$distritos['Baishan'] = ['Hunjiang', 'Jiangyuan', 'Linjiang', 'Fusong', 'Jingyu', 'Changbai'];
$distritos['Songyuan'] = ['Ningjiang', 'Fuyu', 'Qian\'an', 'Changling', 'Qian Gorlos'];
$distritos['Baicheng'] = ['Taobei', 'Taonan', 'Da\'an', 'Zhenlai', 'Tongyu'];
$distritos['Yanbian'] = ['Yanji', 'Tumen', 'Dunhua', 'Hunchun', 'Longjing', 'Helong', 'Antu', 'Wangqing'];

// Heilongjiang
$distritos['Harbin'] = ['Daoli', 'Daowai', 'Nangang', 'Xiangfang', 'Pingfang', 'Songbei', 'Hulan', 'Acheng', 'Shuangcheng', 'Shangzhi', 'Wuchang', 'Yilan', 'Fangzheng', 'Binxian', 'Bayan', 'Mulan', 'Tonghe', 'Yanshou'];
$distritos['Qiqihar'] = ['Longsha', 'Jianhua', 'Tiefeng', 'Angangxi', 'Fularji', 'Nianzishan', 'Meilisi', 'Nehe', 'Longjiang', 'Yi\'an', 'Tailai', 'Gannan', 'Fuyu', 'Keshan', 'Kedong', 'Baiquan'];
$distritos['Mudanjiang'] = ['Dongan', 'Yangming', 'Aiming', 'Xi\'an', 'Muling', 'Suifenhe', 'Hailin', 'Ning\'an', 'Dongning', 'Linkou'];
$distritos['Jiamusi'] = ['Qianjin', 'Xiangyang', 'Dongfeng', 'Jiaoqu', 'Tongjiang', 'Fujin', 'Fuyuan', 'Huanan', 'Huachuan', 'Tangyuan'];
$distritos['Daqing'] = ['Saertu', 'Longfeng', 'Ranghulu', 'Datong', 'Honggang', 'Zhaozhou', 'Zhaoyuan', 'Lindian', 'Dorbod'];
$distritos['Jixi'] = ['Jiguan', 'Hengshan', 'Didao', 'Lishu', 'Chengzihe', 'Mashan', 'Hulin', 'Mishan', 'Jidong'];
$distritos['Hegang'] = ['Xiangyang', 'Gongnong', 'Nanshan', 'Xing\'an', 'Dongshan', 'Xingshan', 'Luobei', 'Suibin'];
$distritos['Shuangyashan'] = ['Jianshan', 'Lingdong', 'Sifangtai', 'Baoshan', 'Jixian', 'Youyi', 'Baoqing', 'Raohe'];
$distritos['Yichun'] = ['Yimei', 'Wucui', 'Youhao', 'Jinlin', 'Tieli', 'Jiayin', 'Tangwang', 'Fenglin', 'Nancha', 'Daqingshan'];
$distritos['Qitaihe'] = ['Taoshan', 'Xinxing', 'Qiezihe', 'Boli'];
$distritos['Heihe'] = ['Aihui', 'Bei\'an', 'Wudalianchi', 'Nenjiang', 'Xunke', 'Sunwu'];
$distritos['Suihua'] = ['Beilin', 'Anda', 'Zhaodong', 'Hailun', 'Wanghui', 'Lanxi', 'Qinggang', 'Qing\'an', 'Mingshui', 'Suiling'];
$distritos['Daxing\'anling'] = ['Mohe', 'Tahe', 'Huma', 'Jiagedaqi', 'Songling', 'Xinlin'];

// Shaanxi
$distritos["Xi'an"] = ['Xincheng', 'Beilin', 'Lianhu', 'Baqiao', 'Weiyang', 'Yanta', 'Yanliang', 'Lintong', 'Chang\'an', 'Gaoling', 'Huyi', 'Lantian', 'Zhouzhi'];
$distritos['Baoji'] = ['Weibin', 'Jintai', 'Chencang', 'Fengxiang', 'Qishan', 'Fufeng', 'Meixian', 'Longxian', 'Qianyang', 'Linyou', 'Fengxian', 'Taibai'];
$distritos['Xianyang'] = ['Qindu', 'Weicheng', 'Yangling', 'Xingping', 'Sanyuan', 'Jingyang', 'Qianxian', 'Liquan', 'Yongshou', 'Changwu', 'Xunyi', 'Chunhua', 'Wugong', 'Binzhou'];
$distritos['Tongchuan'] = ['Wangyi', 'Yintai', 'Yao\'an', 'Yijun'];
$distritos['Weinan'] = ['Linwei', 'Huazhou', 'Hancheng', 'Huayin', 'Tongguan', 'Dali', 'Heyang', 'Chengcheng', 'Pucheng', 'Baishui', 'Fuping'];
$distritos['Yan\'an'] = ['Baota', 'Ansai', 'Yanchang', 'Yanchuan', 'Zichang', 'Zhidan', 'Wuqi', 'Ganquan', 'Fu', 'Luochuan', 'Yichuan', 'Huanglong', 'Huangling'];
$distritos['Hanzhong'] = ['Hantai', 'Nanzheng', 'Chenggu', 'Yangxian', 'Xixiang', 'Mianxian', 'Ningqiang', 'Lueyang', 'Zhenba', 'Liuba', 'Foping'];
$distritos['Yulin'] = ['Yuyang', 'Hengshan', 'Shenmu', 'Fugu', 'Jingbian', 'Dingbian', 'Suide', 'Mizhi', 'Jia', 'Wubu', 'Qingjian', 'Zizhou'];
$distritos['Ankang'] = ['Hanbin', 'Hanyin', 'Shiquan', 'Ningshan', 'Ziyang', 'Langao', 'Pingli', 'Zhenping', 'Xunyang', 'Baihe'];
$distritos['Shangluo'] = ['Shangzhou', 'Luonan', 'Danfeng', 'Shangnan', 'Shanyang', 'Zhen\'an', 'Zhashui'];

// Hebei
$distritos['Shijiazhuang'] = ['Chang\'an', 'Qiaoxi', 'Xinhua', 'Yuhua', 'Jingxing', 'Luancheng', 'Gaocheng', 'Luquan', 'Jingxing', 'Zhengding', 'Xingtang', 'Lingshou', 'Gaoyi', 'Shenze', 'Zanhuang', 'Wuji', 'Pingshan', 'Yuanshi', 'Zhao', 'Xinji', 'Jinzhou', 'Xinle'];
$distritos['Tangshan'] = ['Lubei', 'Lunan', 'Guye', 'Kaiping', 'Fengnan', 'Fengrun', 'Caofeidian', 'Zunhua', 'Qian\'an', 'Luannan', 'Leting', 'Qianxi', 'Yutian'];
$distritos['Handan'] = ['Congtai', 'Hanshan', 'Fuxing', 'Fengfeng', 'Feixiang', 'Yongnian', 'Linzhang', 'Cheng\'an', 'Daming', 'She', 'Ci', 'Qiu', 'Jize', 'Guangping', 'Guantao', 'Wei', 'Quzhou', 'Wu\'an'];
$distritos['Baoding'] = ['Jingxiu', 'Lianchi', 'Jingcheng', 'Mancheng', 'Qingyuan', 'Xushui', 'Anguo', 'Gaobeidian', 'Laiyuan', 'Laishui', 'Dingxing', 'Gaoyang', 'Rongcheng', 'Lixian', 'Shunping', 'Tangxian', 'Wangdu', 'Laishui', 'Fuping', 'Zhuozhou', 'Dingzhou'];
$distritos['Zhangjiakou'] = ['Qiaodong', 'Qiaoxi', 'Xuanhua', 'Xiahuayuan', 'Wanquan', 'Chongli', 'Zhangbei', 'Kangbao', 'Guyuan', 'Huai\'an', 'Xuanhua', 'Zhuolu', 'Wei', 'Yangyuan', 'Huailai', 'Chicheng'];
$distritos['Chengde'] = ['Shuangqiao', 'Shuangluan', 'Yingshouyingzi', 'Pingquan', 'Luanping', 'Longhua', 'Fengning', 'Kuancheng', 'Weichang', 'Xinglong'];
$distritos['Cangzhou'] = ['Xinhua', 'Yunhe', 'Botou', 'Renqiu', 'Huanghua', 'Hejian', 'Cangxian', 'Qingxian', 'Dongguang', 'Haixing', 'Yanshan', 'Suning', 'Nanpi', 'Wuqiao', 'Xianxian', 'Mengcun'];
$distritos['Langfang'] = ['Anci', 'Guangyang', 'Bazhou', 'Sanhe', 'Yanjiao', 'Gu\'an', 'Yongqing', 'Xianghe', 'Dacheng', 'Wen\'an', 'Dachang'];
$distritos['Hengshui'] = ['Taocheng', 'Jizhou', 'Shenzhou', 'Zaoqiang', 'Wuyi', 'Wuqiang', 'Raoyang', 'Anping', 'Gucheng', 'Jingxian', 'Fucheng'];
$distritos['Xingtai'] = ['Xiangdu', 'Qiaodong', 'Xingtaixian', 'Lincheng', 'Neiqiu', 'Baixiang', 'Longyao', 'Renxian', 'Nanhe', 'Ningjin', 'Julou', 'Xinhe', 'Guangzong', 'Pingxiang', 'Weixian', 'Qinghe', 'Shahe'];
$distritos['Qinhuangdao'] = ['Haigang', 'Shanhaiguan', 'Beidaihe', 'Changli', 'Funing', 'Lulong', 'Qinglong'];

// Hainan
$distritos['Haikou'] = ['Xiuying', 'Longhua', 'Qiongshan', 'Meilan'];
$distritos['Sanya'] = ['Jiyang', 'Tianya', 'Haitang', 'Yuzhou'];
$distritos['Sansha'] = ['Xisha', 'Nansha', 'Zhongsha'];
$distritos['Danzhou'] = ['Danzhou'];

// Gansu
$distritos['Lanzhou'] = ['Chengguan', 'Qilihe', 'Xigu', 'Anning', 'Honggu', 'Yongdeng', 'Gaolan', 'Yuzhong'];
$distritos['Tianshui'] = ['Qincheng', 'Maiji', 'Qingshui', 'Qin\'an', 'Gangu', 'Wushan', 'Zhangjiachuan'];
$distritos['Baiyin'] = ['Baiyin', 'Pingchuan', 'Jingyuan', 'Huining', 'Jingtai'];
$distritos['Wuwei'] = ['Liangzhou', 'Minqin', 'Gulang', 'Tianzhu'];
$distritos['Zhangye'] = ['Ganzhou', 'Minle', 'Linze', 'Gaotai', 'Shandan', 'Yugur', 'Sunan'];
$distritos['Jiuquan'] = ['Suzhou', 'Yumen', 'Dunhuang', 'Guazhou', 'Jinta', 'Aksai', 'Subei'];
$distritos['Pingliang'] = ['Kongtong', 'Kongtong', 'Jingchuan', 'Lingtai', 'Chongxin', 'Huating', 'Zhuanglang', 'Jingning'];
$distritos['Qingyang'] = ['Xifeng', 'Qingcheng', 'Huanxian', 'Huachi', 'Heshui', 'Zhengning', 'Ningxian', 'Zhenyuan'];
$distritos['Dingxi'] = ['Anding', 'Tongwei', 'Longxi', 'Weiyuan', 'Lintao', 'Zhangxian', 'Minxian'];
$distritos['Longnan'] = ['Wudu', 'Chengxian', 'Tanchang', 'Kangxian', 'Wenxian', 'Xihe', 'Lixian', 'Hui', 'Liangdang'];
$distritos['Linxia'] = ['Linxia', 'Linxiaxian', 'Kangle', 'Yongjing', 'Guanghe', 'Hezheng', 'Dongxiang', 'Jishishan'];
$distritos['Gannan'] = ['Hezuo', 'Lintan', 'Zhuoni', 'Zhouqu', 'Diebu', 'Maqu', 'Luqu', 'Xiahe'];

// Qinghai
$distritos['Xining'] = ['Chengdong', 'Chengzhong', 'Chengxi', 'Chengbei', 'Huangzhong', 'Huangyuan', 'Datong'];
$distritos['Haidong'] = ['Ledu', 'Ping\'an', 'Minhe', 'Huzhu', 'Xunhua', 'Hualong', 'Huangnan'];
$distritos['Haibei'] = ['Haiyan', 'Qilian', 'Gangca', 'Menyuan'];
$distritos['Huangnan'] = ['Tongren', 'Jianzha', 'Zeku', 'Henan'];
$distritos['Hainan (Qinghai)'] = ['Gonghe', 'Tongde', 'Guide', 'Xinghai', 'Guinan'];
$distritos['Golog'] = ['Maqen', 'Banma', 'Gade', 'Darlag', 'Jigzhi', 'Madoi'];
$distritos['Yushu'] = ['Yushu', 'Zadoi', 'Chindu', 'Zhidoi', 'Nangqen', 'Qumarlib'];
$distritos['Haixi'] = ['Delingha', 'Golmud', 'Mangnai', 'Ulan', 'Dulan', 'Tianjun', 'Da Qaidam', 'Lenghu'];

// Xizang (Tibet)
$distritos['Lhasa'] = ['Chengguan', 'Doilungdeqen', 'Dagze', 'Nyemo', 'Maizhokunggar', 'Damxung', 'Lhunzhub', 'Qushui'];
$distritos['Shigatse'] = ['Samzhubzê', 'Namling', 'Lhazê', 'Bainang', 'Panam', 'Kangmar', 'Dinggyê', 'Lhozha', 'Saga', 'Gyangzê', 'Tingri', 'Sa\'gya', 'Rinbung', 'Xaitongmoin', 'Ngamring', 'Gamba', 'Yadong', 'Nyalam', 'Zhongba', 'Kamba'];
$distritos['Chamdo'] = ['Karub', 'Jomda', 'Gonjo', 'Riwoche', 'Dengqen', 'Zhag\'yab', 'Baxoi', 'Zogang', 'Markam', 'Lhorong', 'Banbar'];
$distritos['Nyingchi'] = ['Bayi', 'Gongbo\'gyamda', 'Mainling', 'Medog', 'Zayü', 'Nangxian'];
$distritos['Nagqu'] = ['Seni', 'Lhari', 'Biru', 'Nyainrong', 'Amdo', 'Xainza', 'Baqên', 'Sog', 'Shuanghu', 'Nyima'];
$distritos['Ngari'] = ['Gar', 'Burang', 'Zanda', 'Rutog', 'Gê\'gyai', 'Gêrzê', 'Coqên'];

// Ningxia
$distritos['Yinchuan'] = ['Xingqing', 'Jinfeng', 'Xixia', 'Yongning', 'Helan', 'Lingwu'];
$distritos['Shizuishan'] = ['Dawukou', 'Huinong', 'Pingluo'];
$distritos['Wuzhong'] = ['Liwutong', 'Hongsibu', 'Qingtongxia', 'Yanchi', 'Tongxin'];
$distritos['Guyuan'] = ['Yuanzhou', 'Xiji', 'Longde', 'Jingyuan', 'Pengyang'];
$distritos['Zhongwei'] = ['Shapotou', 'Zhongning', 'Haiyuan'];

// Xinjiang
$distritos['Urumqi'] = ['Tianshan', 'Saybagh', 'Xinshi', 'Shuimogou', 'Toutunhe', 'Dabancheng', 'Midong', 'Ürümqi'];
$distritos['Karamay'] = ['Karamay', 'Dushanzi', 'Baijiantan', 'Orku'];
$distritos['Turpan'] = ['Gaochang', 'Shanshan', 'Toksun'];
$distritos['Hami'] = ['Yizhou', 'Yiwu', 'Barkol'];
$distritos['Changji'] = ['Changji', 'Fukang', 'Hutubi', 'Manas', 'Qitai', 'Jimsar', 'Mori'];
$distritos['Bayingolin'] = ['Korla', 'Luntai', 'Yuli', 'Ruoqiang', 'Qiemo', 'Hejing', 'Hoxud', 'Bohu', 'Yanqi'];
$distritos['Aksu'] = ['Aksu', 'Wensu', 'Kuche', 'Shaya', 'Xinhe', 'Baicheng', 'Wushi', 'Kalpin', 'Awat'];
$distritos['Kashgar'] = ['Kashgar', 'Shufu', 'Shule', 'Yengisar', 'Poskam', 'Yarkant', 'Kargilik', 'Makit', 'Pishan', 'Maralbexi', 'Taxkorgan', 'Payzawat'];
$distritos['Kizilsu'] = ['Artux', 'Akqi', 'Ulugqat', 'Akto'];
$distritos['Hotan'] = ['Hotan', 'Hotan', 'Karaqax', 'Pishan', 'Lop', 'Keriye', 'Moyu', 'Qira', 'Minfeng'];
$distritos['Ili'] = ['Yining', 'Korgas', 'Kuytun', 'Nilka', 'Zhaosu', 'Tekes', 'Qapqal', 'Huocheng', 'Gongliu', 'Xinyuan'];
$distritos['Tacheng'] = ['Tacheng', 'Wusu', 'Emin', 'Yumin', 'Shawan', 'Toli', 'Hoboksar'];
$distritos['Altay'] = ['Altay', 'Burqin', 'Fuyun', 'Fuhai', 'Habahe', 'Qinggil', 'Jeminay'];

// Nei Mongol
$distritos['Hohhot'] = ['Huimin', 'Xincheng', 'Yuquan', 'Saihan', 'Togtoh', 'Wuchuan', 'Horinger', 'Qingshuihe', 'Tumed'];
$distritos['Baotou'] = ['Donghe', 'Hondlon', 'Qingshan', 'Shiguai', 'Bayan Obo', 'Jiuyuan', 'Guyang', 'Darhan Muminggan', 'Tumed Right'];
$distritos['Wuhai'] = ['Haibowan', 'Hainan', 'Wuda'];
$distritos['Chifeng'] = ['Hongshan', 'Yuanbaoshan', 'Songshan', 'Ningcheng', 'Linxi', 'Ar Horqin', 'Bairin Left', 'Bairin Right', 'Hexigten', 'Ongniud', 'Harqin', 'Aohan'];
$distritos['Tongliao'] = ['Horqin', 'Horqin Left Middle', 'Kailu', 'Hure', 'Naiman', 'Jarud', 'Hexigten'];
$distritos['Erdos'] = ['Dongsheng', 'Kangbashi', 'Dalad', 'Jungar', 'Otog Front', 'Otog', 'Hanggin', 'Uxin', 'Ejin Horo'];
$distritos['Hulunbuir'] = ['Hailar', 'Zhalainuoer', 'Manzhouli', 'Zalantun', 'Yakeshi', 'Genhe', 'Ergun', 'Arun', 'Morin Dawa', 'Evenki', 'Old Barag', 'New Barag Left', 'New Barag Right'];
$distritos['Bayannur'] = ['Linhe', 'Wuyuan', 'Dengkou', 'Urad Front', 'Urad Middle', 'Urad Rear', 'Hanggin Rear'];
$distritos['Ulanqab'] = ['Jining', 'Fengzhen', 'Zhuozi', 'Huade', 'Shangdu', 'Xinghe', 'Liangcheng', 'Qahar Right Front', 'Qahar Right Middle', 'Qahar Right Rear', 'Siziwang', 'Dorbod'];

// Shanxi
$distritos['Taiyuan'] = ['Xinghualing', 'Jiefang', 'Wanbailin', 'Yingze', 'Jiancaoping', 'Jinyuan', 'Gujiao', 'Qingxu', 'Yangqu', 'Loufan'];
$distritos['Datong'] = ['Pingcheng', 'Yungang', 'Xinrong', 'Yunzhou', 'Yanggao', 'Tianguzhen', 'Guangling', 'Lingqiu', 'Hunyuan', 'Zuoyun'];
$distritos['Yangquan'] = ['Chengqu', 'Jiaoqu', 'Kuangqu', 'Pingding', 'Yu'];
$distritos['Changzhi'] = ['Luzhou', 'Lucheng', 'Zhangzi', 'Tunliu', 'Xiangyuan', 'Pingshun', 'Licheng', 'Huguan', 'Wuxiang', 'Qiu', 'Qinyuan'];
$distritos['Jincheng'] = ['Chengqu', 'Gaoping', 'Zezhou', 'Qinshui', 'Yangcheng', 'Lingchuan'];
$distritos['Shuozhou'] = ['Shuocheng', 'Pinglu', 'Shanyin', 'Yingxian', 'Youyu', 'Huairen'];
$distritos['Jinzhong'] = ['Yuci', 'Jiexiu', 'Yushe', 'Zuoquan', 'Heshun', 'Xiyang', 'Shouyang', 'Taigu', 'Qi', 'Pingyao', 'Lingshi'];
$distritos['Yuncheng'] = ['Yanhu', 'Yongji', 'Hejin', 'Linyi', 'Wanrong', 'Wenxi', 'Jishan', 'Xinjiang', 'Xia', 'Jiangxian', 'Yuanqu', 'Ruicheng', 'Pinglu'];
$distritos['Xinzhou'] = ['Xinfu', 'Yuanping', 'Dingxiang', 'Wutai', 'Dai', 'Fanshi', 'Ningwu', 'Jingle', 'Shenchi', 'Wuzhai', 'Kelan', 'Hequ', 'Baode', 'Piankuan'];
$distritos['Linfen'] = ['Yaodu', 'Houma', 'Huozhou', 'Quwo', 'Yicheng', 'Xiangfen', 'Hongdong', 'Gu', 'Anze', 'Fushan', 'Ji', 'Xiangning', 'Pu', 'Daning', 'Yonghe', 'Xi', 'Fenxi'];
$distritos['Lüliang'] = ['Lishi', 'Xiaoyi', 'Fenyang', 'Wenshui', 'Zhongyang', 'Xingxian', 'Linxian', 'Fangshan', 'Liulin', 'Lanxian', 'Jiaokou', 'Jiaocheng', 'Shilou'];

// Taiwan
$distritos['Taipei'] = ['Zhongzheng', 'Datong', 'Wanhua', 'Zhongshan', 'Songshan', 'Da\'an', 'Nangang', 'Neihu', 'Shilin', 'Beitou'];
$distritos['Kaohsiung'] = ['Yancheng', 'Gushan', 'Zuoying', 'Nanzi', 'Sanmin', 'Xiaogang', 'Qianjin', 'Lingya', 'Qianzhen', 'Meinong', 'Fengshan', 'Gangshan', 'Cishan', 'Qishan', 'Dashu', 'Daliao', 'Linyuan', 'Mituo', 'Yong\'an', 'Luzhu', 'Neimen', 'Tianliao', 'Jiaxian', 'Liugui', 'Maolin', 'Taoyuan', 'Namasia', 'Hunei', 'Alian', 'Ziguan', 'Qieding'];
$distritos['New Taipei'] = ['Banqiao', 'Xinzhuang', 'Zhonghe', 'Yonghe', 'Shulin', 'Tucheng', 'Sanchong', 'Luzhou', 'Taishan', 'Xizhi', 'Shenkeng', 'Shiding', 'Pinglin', 'Sanxia', 'Yingge', 'Danshui', 'Jinshan', 'Wanli', 'Xindian', 'Pingxi', 'Shuangxi', 'Gongliao', 'Ruifang', 'Linkou', 'Wu\'lai', 'Bali', 'Tamsui'];
$distritos['Taichung'] = ['Central', 'East', 'South', 'West', 'North', 'Beitun', 'Xitun', 'Nantun', 'Dali', 'Wufeng', 'Taiping', 'Shalu', 'Wuqi', 'Qingshui', 'Longjing', 'Dajia', 'Waipu', 'Houli', 'Fengyuan', 'Dongshi', 'Xinshe', 'Shigang', 'Heping', 'Daan'];
$distritos['Tainan'] = ['West Central', 'East', 'South', 'West', 'North', 'Anping', 'Annan', 'Yongkang', 'Guiren', 'Xinhua', 'Zuozhen', 'Shanhua', 'Danei', 'Anding', 'Jiali', 'Xigang', 'Qigu', 'Jiangjun', 'Xuejia', 'Beimen', 'Madou', 'Xiaying', 'Guanmiao', 'Nanhua', 'Dongshan', 'Liuying', 'Baihe', 'Houbi', 'Yanshui', 'Guantian', 'Rende'];
$distritos['Taoyuan'] = ['Taoyuan', 'Zhongli', 'Pingzhen', 'Bade', 'Yangmei', 'Guishan', 'Luzhu', 'Dayuan', 'Guanyin', 'Xinwu', 'Longtan', 'Fuxing'];

// Guangxi
$distritos['Nanning'] = ['Xingning', 'Qingxiu', 'Jiangnan', 'Xixiangtang', 'Yongning', 'Liangqing', 'Wuming', 'Long\'an', 'Mashan', 'Shanglin', 'Binyang', 'Hengzhou'];
$distritos['Guilin'] = ['Xiufeng', 'Diecai', 'Xiangshan', 'Qixing', 'Yanshan', 'Lingui', 'Yangshuo', 'Lingchuan', 'Quanzhou', 'Xing\'an', 'Yongfu', 'Guanyang', 'Ziyuan', 'Pingle', 'Lipu', 'Gongcheng', 'Longsheng', 'Resource'];
$distritos['Liuzhou'] = ['Chengzhong', 'Yufeng', 'Liunan', 'Liubei', 'Liujiang', 'Liucheng', 'Luzhai', 'Rong\'an', 'Rongshui', 'Sanjiang'];
$distritos['Wuzhou'] = ['Wanxiu', 'Changzhou', 'Longwei', 'Cenxi', 'Cangwu', 'Tengxian', 'Mengshan'];
$distritos['Beihai'] = ['Haicheng', 'Yinhai', 'Tieshangang', 'Hepu'];
$distritos['Fangchenggang'] = ['Gangkou', 'Fangcheng', 'Dongxing', 'Shangsi'];
$distritos['Qinzhou'] = ['Qinnan', 'Qinbei', 'Lingshan', 'Pubei'];
$distritos['Guigang'] = ['Gangbei', 'Gangnan', 'Qintang', 'Guiping', 'Pingnan'];
$distritos['Yulin'] = ['Yuzhou', 'Fumian', 'Bobai', 'Xingye', 'Rongxian', 'Luchuan', 'Beiliu'];
$distritos['Baise'] = ['Youjiang', 'Tianyang', 'Tianyang', 'Lingyun', 'Pingguo', 'Xilin', 'Leye', 'Debao', 'Tianlin', 'Longlin', 'Jingxi', 'Napo'];
$distritos['Hezhou'] = ['Babu', 'Pinggui', 'Zhaoping', 'Zhongshan', 'Fuchuan'];
$distritos['Hechi'] = ['Jinchengjiang', 'Yizhou', 'Nandan', 'Tian\'e', 'Fengshan', 'Donglan', 'Luocheng', 'Huanjiang', 'Bama', 'Du\'an', 'Dahua'];
$distritos['Laibin'] = ['Xingbin', 'Heshan', 'Xincheng', 'Xiangzhou', 'Wuxuan', 'Jinxiu'];
$distritos['Chongzuo'] = ['Jiangzhou', 'Pingxiang', 'Ningming', 'Fusui', 'Longzhou', 'Daxin', 'Tiandeng'];

// Guizhou remaining
$distritos['Ganzi'] = ['Kangding', 'Luding', 'Danba', 'Jiulong', 'Yajiang', 'Daofu', 'Luhuo', 'Ganzi', 'Xinlong', 'Dege', 'Baiyu', 'Shiqu', 'Seda', 'Litang', 'Batang', 'Xiangcheng', 'Daocheng', 'Derong'];
$distritos['Aba (Ngawa)'] = ['Barkam', 'Wenchuan', 'Li', 'Mao', 'Songpan', 'Jiuzhaigou', 'Jinchuan', 'Xiaojin', 'Heishui', 'Zamtang', 'Aba', 'Zoige', 'Hongyuan', 'Rangtang'];

// Need to map remaining Sichuan prefectures
$distritos['Bazhong'] = ['Bazhou', 'Enyang', 'Tongjiang', 'Nanjiang'];
$distritos['Dazhou'] = ['Tongchuan', 'Dachuan', 'Wanyuan', 'Xuanhan', 'Kaijiang', 'Dazhu', 'Qu'];
$distritos['Guang\'an'] = ['Guang\'an', 'Qianfeng', 'Huaying', 'Yuechi', 'Wusheng', 'Linshui'];
$distritos['Guangyuan'] = ['Lizhou', 'Zhaohua', 'Chaotian', 'Wangcang', 'Qingchuan', 'Jiange', 'Cangxi'];
$distritos['Leshan'] = ['Shizhong', 'Shawan', 'Wutongqiao', 'Jinkouhe', 'Emeishan', 'Qianwei', 'Jingyan', 'Jiajiang', 'Muchuan', 'Ebian', 'Mabian'];
$distritos['Liangshan'] = ['Xichang', 'Huili', 'Yanyuan', 'Dechang', 'Mianning', 'Butuo', 'Jinyang', 'Zhaojue', 'Xide', 'Mianning', 'Ningnan', 'Puge', 'Leibo', 'Meigu', 'Ganluo', 'Yuexi', 'Muli'];
$distritos['Meishan'] = ['Dongpo', 'Pengshan', 'Renshou', 'Hongya', 'Danleng', 'Qingshen'];
$distritos['Neijiang'] = ['Shizhong', 'Dongxing', 'Weiyuan', 'Zizhong', 'Longchang'];
$distritos['Panzhihua'] = ['Dongqu', 'Xiqu', 'Renhe', 'Miyi', 'Yanbian'];
$distritos['Suining'] = ['Chuanshan', 'Anju', 'Pengxi', 'Shehong', 'Daying'];
$distritos['Ya\'an'] = ['Yucheng', 'Mingshan', 'Yingjing', 'Hanyuan', 'Shimian', 'Tianquan', 'Lushan', 'Baoxing'];
$distritos['Zigong'] = ['Ziliujing', 'Gongjing', 'Da\'an', 'Yantan', 'Rongxian', 'Fushun'];
$distritos['Ziyang'] = ['Yanjiang', 'Lezhi', 'Anyue'];

// Jilin remaining
$distritos['Yanbian'] = ['Yanji', 'Tumen', 'Dunhua', 'Hunchun', 'Longjing', 'Helong', 'Antu', 'Wangqing'];

// Prepare distrito data
$dist_data = [];
foreach ($distritos as $prov_nombre => $dist_list) {
    foreach ($dist_list as $dist_nombre) {
        // Find which provincia this belongs to
        foreach ($prov_map as $depto_nombre => $provs) {
            if (isset($provs[$prov_nombre])) {
                $dist_data[] = ['provincia_id' => $provs[$prov_nombre], 'nombre' => $dist_nombre];
                break;
            }
        }
    }
}

// Insert in chunks
$total_dist = count($dist_data);
foreach (array_chunk($dist_data, 100) as $chunk) {
    DB::table('distritos')->insert($chunk);
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');
echo "Inserted $total_dist distritos for China.\n";
echo "Done!\n";
