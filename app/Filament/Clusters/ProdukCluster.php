<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class ProdukCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Produk Management';

    protected static ?string $slug = 'Produk-Management';
}
