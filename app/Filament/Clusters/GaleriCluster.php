<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class GaleriCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-photo';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Galeri Management';

    protected static ?string $slug = 'Galeri-Management';
}
