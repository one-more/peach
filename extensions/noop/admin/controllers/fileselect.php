<?php
namespace noop_admin;

/**
 * Class fileselectcontroller
 *
 * @package noop_admin
 *
 * @author Nikolaev D.
 */
class fileselectcontroller extends \supercontroller {
    /**
     * @return string
     */
    public function display()
    {
        $defaults = [
            'base_dir'      => '/www/media',
            'accept_type'   => 'file',
            'file_filter'   => 'all',
            'level'         => 1
        ];

        $data = array_merge($defaults, $_REQUEST);

        /*-------------------------------- make ul ----------------------------------------------*/
        if($data['level'] == 1 || $data['level'] == 3) {
            $iterator = new \FilesystemIterator(SITE_PATH.$data['base_dir']);
        }
        else {
            $iterator = new \FilesystemIterator($data['base_dir']);
        }

        $lis = '';
        /*---------------- start switch --------------------------------------*/
        switch($data['file_filter']) {
            case 'image':
                $exts = ['png', 'jpg', 'jpeg', 'ico', 'gif', 'bmp'];
                foreach($iterator as $el) {
                    if($el->isDir()) {
                        $lis .= \dom::create_element(
                            'li',
                            [
                                'class' => 'width-15 margin-0 padding-0 text-center',
                                'text'  => \templator::getTemplate(
                                        $data['accept_type'] == 'file' ? 'dir' : 'submenu_dir',
                                        [
                                            'path'  => $el->getPathname(),
                                            'name'  => \helper::get_filename($el->getFilename()),
                                            'title' => $el->getFilename()
                                        ],
                                        \noop::$path.'admin'.DS.'views'.DS.'fileselect'
                                    )
                            ]
                        );
                    }
                    else {
                        if(in_array($el->getExtension(), $exts)) {
                            $lis .= \dom::create_element(
                                'li',
                                [
                                    'class' => 'width-15 margin-0 padding-0 text-center',
                                    'text'  => \templator::getTemplate(
                                            'file',
                                            [
                                                'path'  => $el->getPathname(),
                                                'name'  => \helper::get_filename($el->getFilename()),
                                                'title' => $el->getFilename()
                                            ],
                                            \noop::$path.'admin'.DS.'views'.DS.'fileselect'
                                        )
                                ]
                            );
                        }
                    }
                }
                break;
            case 'text':
                foreach($iterator as $el) {
                    if($el->isDir()) {
                        $lis .= \dom::create_element(
                            'li',
                            [
                                'class' => 'width-15 margin-0 padding-0 text-center',
                                'text'  => \templator::getTemplate(
                                        $data['accept_type'] == 'file' ? 'dir' : 'submenu_dir',
                                        [
                                            'path'  => $el->getPathname(),
                                            'name'  => \helper::get_filename($el->getFilename()),
                                            'title' => $el->getFilename()
                                        ],
                                        \noop::$path.'admin'.DS.'views'.DS.'fileselect'
                                    )
                            ]
                        );
                    }
                    else {
                        if($el->getExtension() == 'txt') {
                            $lis .= \dom::create_element(
                                'li',
                                [
                                    'class' => 'width-15 margin-0 padding-0 text-center',
                                    'text'  => \templator::getTemplate(
                                            'file',
                                            [
                                                'path'  => $el->getPathname(),
                                                'name'  => \helper::get_filename($el->getFilename()),
                                                'title' => $el->getFilename()
                                            ],
                                            \noop::$path.'admin'.DS.'views'.DS.'fileselect'
                                        )
                                ]
                            );
                        }
                    }
                }
                break;
            case 'all':
            default:
                foreach($iterator as $el) {
                    if($el->isDir()) {
                        $lis .= \dom::create_element(
                            'li',
                            [
                                'class' => 'width-15 margin-0 padding-0 text-center',
                                'text'  => \templator::getTemplate(
                                        $data['accept_type'] == 'file' ? 'dir' : 'submenu_dir',
                                        [
                                            'path'  => $el->getPathname(),
                                            'name'  => \helper::get_filename($el->getFilename()),
                                            'title' => $el->getFilename()
                                        ],
                                        \noop::$path.'admin'.DS.'views'.DS.'fileselect'
                                    )
                            ]
                        );
                    }
                    else {
                        $lis .= \dom::create_element(
                            'li',
                            [
                                'class' => 'width-15 margin-0 padding-0 text-center',
                                'text'  => \templator::getTemplate(
                                        'file',
                                        [
                                            'path'  => $el->getPathname(),
                                            'name'  => \helper::get_filename($el->getFilename()),
                                            'title' => $el->getFilename()
                                        ],
                                        \noop::$path.'admin'.DS.'views'.DS.'fileselect'
                                    )
                            ]
                        );
                    }
                }
                break;

        }
        /*--------------------- end switch ---------------------------------------------*/

        $params = [];

        $params['ul'] = \dom::create_element(
            'ul',
            [
                'text'  => $lis,
                'class' => 'inline-ul width-100 margin-0 padding-0 custom-file-select-ul'
            ]
        );

        $refs = \factory::get_reference('custom_file_select');

        $params['info'] = $refs[$data['file_filter']];
        /*---------------------------------------------------------------------------------------*/

        if($data['level'] == 1) {

            return \templator::getTemplate(
                        'index',
                        $params,
                        \noop::$path.'admin'.DS.'views'.DS.'fileselect'
                    );

        }
        else {
            return $params['ul'];
        }
    }
}