<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Post;

/**
 * PostSearch represents the model behind the search form of `app\models\Post`.
 */
class PostSearch extends Post
{
    /** @var string */
    public $date_from;
    
    /** @var string */
    public $date_to;
    
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'slug', 'body', 'created_by'], 'safe'],
            [['date_from', 'date_to'], 'string'],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = Post::find();
        
        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->setAttributes($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // TODO add created at between interval search
        $query
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['>=', 'created_at', $this->date_from])
            ->andFilterWhere(['<', 'created_at', $this->date_to])
            ->andFilterWhere(['=', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
