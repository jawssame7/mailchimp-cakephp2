<?php

App::uses('ApiClient', 'MailchimpTransactional');

/**
 * Mailchimp コンポーネント
 */
class MailChimpComponent  extends Component
{

    public ?\MailchimpTransactional\ApiClient $_mailchimp = null;

    /**
     * 初期処理
     * @param Controller $controller
     */
    function initialize(Controller $controller) {
        try {
            $this->_mailchimp = new MailchimpTransactional\ApiClient();

            // https://stackoverflow.com/questions/65232548/why-got-error-invalid-key-from-the-metadata-api-of-mailchimp/65234912
            $this->_mailchimp->setApiKey('');

            $response = $this->_mailchimp->users->ping();
            CakeLog::debug($response);
            $this->sendMail($this->_message);


        } catch (Error $e) {
            echo 'Error: ',  $e->getMessage(), "\n";
            CakeLog::error($e->getMessage());
        }
        //print_r($response);
    }

    function sendMail($message)
    {
//        $txt = '日本語をいれてみる';
//        $message['text'] = $txt;
//        $tpl = $this->getMandrillTemplate();
//        $content = $tpl->text;
//
//        $message['from_email'] = $tpl->from_email;
//        $message['subject'] = $tpl->subject;
//        $message['text'] = $content;

        // cakeのメールテンプレートを使って
//        $content = $this->cakeMailTemplateCompile();
//        $message['text'] = $content['text'];

        //


        try {

//            $response = $this->_mailchimp->messages->send(["message" => $message]);
            $response = $this->sendTemplateMail();
            var_dump($response);
            CakeLog::debug('メール送信');
        } catch (Error $e) {
            echo 'Error: ', $e->getMessage(), "\n";
            CakeLog::error($e->getMessage());
        }
    }

    private $_message = [
        "from_email" => "hogehoge@devsame7.me",
        "subject" => "タイトルサブジェクト",
        "text" => "Welcome to Mailchimp Transactional!",
        "to" => [
            [
                "email" => "test@devsame7.me",
                "type" => "to"
            ],
            [
                "email" => "test2@devsame7.me",
                "type" => "to"
            ],
            [
                "email" => "test3@devsame7.me",
                "type" => "to"
            ],
            [
                "email" => "test4@devsame7.me",
                "type" => "to"
            ]
        ]
    ];

    /**
     * Mandrillに設定してあるTemplateを取得して返す
     * @return Exception|\GuzzleHttp\Exception\RequestException|mixed|\Psr\Http\Message\StreamInterface
     */
    function getMandrillTemplate()
    {
        $response = $this->_mailchimp->templates->info(["name" => "テンプレート日本語名"]);
        //print_r('メールテンプレート');
        var_dump($response);
        CakeLog::debug('メールテンプレート');
        return $response;
    }

    function sendTemplateMail()
    {
        $response = $this->_mailchimp->messages->sendTemplate([
            "template_name" => "template_variable1",
            'template_content'=>[["name"=>'','content'=>'']],
            //"template_content" => [[]],
            "message" => [
                'merge' => true,
                "to" => [
                    [
                        "email" => "test@devsame7.me",
                        "type" => "to"
                    ]
                ],
                'merge_language' => 'handlebars',
                'global_merge_vars' => [
                    [
                        'name' => 'name',
                        'content' => '斎藤たかお'
                    ],
                    [
                        'name' => 'entry_code',
                        'content' => '2'
                    ],
                    [
                        'name' => 'payedUrl',
                        'content' => 'https://www.yahoo.co.jp'
                    ],
                ]
            ],
        ]);
        var_dump($response);
    }

    /**
     * cakePHPのメールテンプレートからコンテンツを取得する
     * @return array
     */
    function cakeMailTemplateCompile()
    {
        App::import ( 'Vendor', 'AppEmail');
        // メールテンプレートの
        $appEmail = new AppEmail();


        //        var_dump($content);
        return $appEmail->compile('test', ['name' => 'テスト名前', 'hoge' => 'メッセージ']);
    }

}