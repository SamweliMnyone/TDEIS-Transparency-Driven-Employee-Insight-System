<?php

namespace App\View\Composers;

use App\Models\ProjectEmployeeAssignment;
use Illuminate\View\View;

class SidebarComposer
{

        public function compose(View $view)
        {
            $totalAssignmentCount = ProjectEmployeeAssignment::count();
            $view->with('totalAssignmentCount', $totalAssignmentCount);
        }

}
