<?php 
namespace App\Traits;

trait Searchable
{
    public function scopeSearch($query, $searchQuery)
    {
        $query->where(function ($query) use ($searchQuery) {
            $columns = $this->getSearchableColumns();

            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $searchQuery . '%');
            }
        });
    }

    protected function getSearchableColumns()
    {
        return $this->searchable ?? [];
    }
}
?>


