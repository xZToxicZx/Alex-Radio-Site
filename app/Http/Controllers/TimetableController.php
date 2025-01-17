<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

use App\Http\Controllers\AuthController;
use App\TimetableSlot;
use App\User;

class TimetableController extends Controller
{
    public function get(Request $req)
    {
        if (!AuthController::hasPerm('view.panel.timetable') && $req->path() == "/panel/timetable")
            return response()->json(['status' => 'err', 'slots' => [], 'selectedWeek' => $selectedWeek], 401);

        $retSlots = [];
        $userOffset = $req->userOffset;
        $cDateFrom = CarbonImmutable::now('+'.$userOffset.':00')->addWeeks($req->weekOffset)->startOfWeek();
        $selectedWeek = $cDateFrom->format('l jS F y').' - '.$cDateFrom->endOfWeek()->format('l jS F y');

        $slots = TimetableSlot::with('user')->whereBetween('timestamp', [$cDateFrom->timestamp, $cDateFrom->endOfWeek()->timestamp])->get();

        for ($hour=0; $hour < 24; $hour++) {
            $daySlots = [];
            for ($day=0; $day < 7; $day++) {
                $currentDate = $cDateFrom->addDays($day)->addHours($hour)->timestamp;
                $user = NULL;
                foreach ($slots as $slot) {
                    if ($slot->timestamp == $currentDate) {
                        $user = ['id' => $slot->user->id, 'name' => $slot->user->name];
                        break;
                    }
                }
                $daySlots[$day] = ['user' => $user, 'time' => $currentDate];
            }
            $retSlots[$hour] = $daySlots;
        }

        return response()->json(['slots' => $retSlots, 'selectedWeek' => $selectedWeek]);
    }

    public function claimSlot(Request $req)
    {
        if (!AuthController::hasPerm("claim.slot") || (!AuthController::hasPerm("claim.dj.slot") && Auth::user()->id != $req->user && $req->user))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        if (!$req->userOffset || !$req->timestamp)
            return response()->json(['status' => 'err', 'err' => 'Failed: Couldnt find one or more of the request parameters.']);

        $user;
        if (!$req->user)
            $user = User::find(Auth::user()->id)->id;
        else
            $user = $req->user;

        $slot = new TimetableSlot;
        $slot->user_id = $user;
        $slot->timestamp = $req->timestamp;
        $slot->save();
        return response()->json(['status' => 'success']);
    }

    public function unclaimSlot(Request $req)
    {
        if (!AuthController::hasPerm("unclaim.slot"))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        if (!$req->userOffset || !$req->timestamp)
            return response()->json(['status' => 'err', 'err' => 'Failed: Couldnt find one or more of the request parameters.']);

        $slot = TimetableSlot::where('timestamp', $req->timestamp)->first();

        $user = User::find(Auth::user()->id);

        if (!$slot->user->id == $user->id && !AuthController::hasPerm("unclaim.dj.slot"))
            return response()->json(['status' => 'err', 'err' => 'Error: You do not own this slot!']);

        $slot->delete();

        return response()->json(['status' => 'success']);
    }

    public function currentSlot()
    {
        $slot = Carbon::now()->minute(0)->second(0)->timestamp;
        $slot = TimetableSlot::where('timestamp', $slot)->first();
        if (!$slot)
            $slot = "AutoDJ";
        else
            $slot = $slot->user->name;

        return response()->json(['live' => $slot]);
    }
}
