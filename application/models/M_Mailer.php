<?php
/**
 * Класс отвечающий за отправку сообщений
 * Class M_Mailer
 */
class M_Mailer {

    private $to='katelyn.k@ya.ru';
    private $title='Сообщение с сайта katelyn.ru';

    /**
     * Отправка внутреннего сообщения
     *
     * @param string $body текст сообщения
     * @return bool
     */
    public function send($body) {
        return mail(
            $this->to,
            $this->title,
            $body
        );
    }

}
