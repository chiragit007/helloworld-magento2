<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Krish\Hello\Block\Adminhtml\Hello;

use Magento\Framework\View\Element\Template;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;
 
    /**
     * @var \SR\Weblog\Model\BlogPostsFactory
     */
    protected $_questionFactory;
 
    /**
     * @var \SR\Weblog\Model\Status
     */
    protected $_status;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \SR\Weblog\Model\BlogPostsFactory $blogPostFactory
     * @param \SR\Weblog\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Krish\Hello\Model\BlogPostsFactory $questionFactory,
        \Krish\Hello\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_questionFactory = $questionFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }
 
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('question_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('post_filter');
    }
 
    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_questionFactory->create()->getCollection();
        $this->setCollection($collection);
 
        parent::_prepareCollection();
        return $this;
    }
 
    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'question_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'question_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'questioner_name',
            [
                'header' => __('Questioner name'),
                'index' => 'questioner_name',
                'class' => 'xxx'
            ]
        );

        $this->addColumn(
            'question',
            [
                'header' => __('Question'),
                'index' => 'question',
                'class' => 'xxx'
            ]
        );
 
        $this->addColumn(
            'publish_date',
            [
                'header' => __('Publish Date'),
                'index' => 'publish_date'
            ]
        );
 
 
        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => $this->_status->getOptionArray()
            ]
        );
 
 
        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'question_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
 
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
 
        return parent::_prepareColumns();
    }
 
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('question_id');
        $this->getMassactionBlock()->setTemplate('Krish_Hello::hello/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('hello');
 
        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('hello/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );
 
        $statuses = $this->_status->getOptionArray();
 
        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('hello/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );
 
 
        return $this;
    }
 
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('hello/*/grid', ['_current' => true]);
    }
 
    /**
     * @param \SR\Weblog\Model\BlogPosts|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'hello/*/edit',
            ['question_id' => $row->getId()]
        );
    }
}