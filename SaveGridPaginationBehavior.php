<?php

namespace marqu3s\behaviors;

use Yii;

/**
 * Saves the Grid's current page in PHP Session on a new page request
 * and use [[getPage()]] to get the current page and assign it
 * to the Pagination configuration.
 *
 * Usage: On the model that will be used to generate the dataProvider
 * that will populate the grid, attach this behavior.
 *
 * ```
 * public function behaviors()
 * {
 *     return [
 *         'saveGridPage' =>[
 *             'class' => SaveGridPaginationBehavior::className(),
 *             'sessionVarName' => self::className() . 'GridPage'
 *         ]
 *     ];
 * }
 * ```
 *
 * Then, on yout search() method, set the grid current page using one of these:
 *
 * ```
 * $dataProvider = new ActiveDataProvider(
 *     [
 *         'query' => $query,
 *         'sort' => ...,
 *         'pagination' => [
 *             'page' => $this->getGridPage(),
 *             ...
 *         ]
 *     ]
 * );
 *
 * OR
 *
 * $dataProvider->pagination->page = $this->getGridPage();
 * ```
 *
 * That's all!
 *
 * @author Joao Marques <joao@jjmf.com>
 */
class SaveGridPaginationBehavior extends MarquesBehavior
{
    /** @var string default $_GET parameter name */
    public $getVarName = 'page';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            \yii\db\BaseActiveRecord::EVENT_INIT => [$this, 'saveGridPage'],
        ];
    }

    /**
     * Saves the grid's current active page index.
     */
    public function saveGridPage()
    {
        if (!isset(Yii::$app->session[$this->sessionVarName])) {
            Yii::$app->session[$this->sessionVarName] = 0;
        }

        if (Yii::$app->request->get($this->getVarName) !== null) {
            Yii::$app->session[$this->sessionVarName] = (int)Yii::$app->request->get($this->getVarName) - 1;
        }
    }

    /**
     * Return the grid's current active page index that was saved before.
     */
    public function getGridPage()
    {
        return Yii::$app->session[$this->sessionVarName];
    }
}
