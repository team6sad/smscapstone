<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Announcement;
use App\User;
use App\Notification;
use Auth;
use Response;
use App\Application;
class StudentAnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student');
    }
    public function checkbox($id)
    {
        try {
            $notification = Notification::findorfail($id);
            if ($notification->is_read) {
                $notification->is_read=0;
            }
            else{
                $notification->is_read=1;
            }
            $notification->save();
            return Response::json($notification);
        } catch(\Exception $e) {
            return "Deleted";
        } 
    }
    public function index()
    {
        $deactivate = Application::find(Auth::id());
        if($deactivate->student_status == 'Graduated' || $deactivate->student_status == 'Forfeit')
            return view('SMS.Student.StudentDeactivate');
        $announcement = Announcement::join('user_announcement','announcements.id','user_announcement.announcement_id')
        ->select('announcements.*','user_announcement.id as user_announcement_id','user_announcement.is_read')
        ->where('user_announcement.user_id',Auth::id())
        ->where('announcements.user_id', function($subquery){
            $subquery->from('users')
            ->join('user_councilor','users.id','user_councilor.user_id')
            ->select('users.id')
            ->where('user_councilor.councilor_id', function($query){
                $query->from('user_councilor')
                ->join('users','user_councilor.user_id','users.id')
                ->select('user_councilor.councilor_id')
                ->where('user_councilor.user_id',Auth::id())
                ->first();
            })
            ->where('users.type','Coordinator')
            ->first();
        })
        ->orderBy('announcements.id','desc')
        ->paginate(10);
        return view('SMS.Student.StudentAnnouncement')->withAnnouncement($announcement);
    }
    public function unread()
    {
        $notification = Notification::join('announcements','user_announcement.announcement_id','announcements.id')
        ->where('user_announcement.is_read',0)
        ->where('announcements.deleted_at',null)
        ->where('user_announcement.user_id',Auth::id())
        ->count();
        return Response::json($notification);
    }
}
