<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class ArtikelsCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-newspaper';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Artikel Management';

    protected static ?string $slug = 'Content-Management';
}
