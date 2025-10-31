<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Notifications extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Get notifications for the logged-in user
     * Returns JSON with unread count and notification list
     */
    public function get(): ResponseInterface
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ])->setStatusCode(401);
        }

        // ✅ Use 'userID' to match the enrollment logic
        $userId = session()->get('userID');

        // Get unread count
        $unreadCount = $this->notificationModel
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();

        // Get recent notifications (last 10)
        $notifications = $this->notificationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     * @param int $id Notification ID
     */
    public function mark_as_read($id = null): ResponseInterface
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ])->setStatusCode(401);
        }

        if ($id === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification ID is required'
            ])->setStatusCode(400);
        }

        // ✅ Use 'userID' to match the enrollment logic
        $userId = session()->get('userID');

        // Verify notification belongs to user - get notification as array
        $db = \Config\Database::connect();
        $notification = $db->table('notifications')->where('id', $id)->get()->getRowArray();

        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found'
            ])->setStatusCode(404);
        }

        if ($notification['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(403);
        }

        // ✅ Update the notification using direct query builder
        $updated = $db->table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        if ($updated) {
            // Get updated unread count - create new model instance to avoid builder issues
            $countModel = new NotificationModel();
            $unreadCount = $countModel
                ->where('user_id', $userId)
                ->where('is_read', 0)
                ->countAllResults();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => $unreadCount
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update notification'
        ])->setStatusCode(500);
    }

    /**
     * Mark all notifications as read for the current user
     */
    public function mark_all_read(): ResponseInterface
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ])->setStatusCode(401);
        }

        // ✅ Use 'userID' to match the enrollment logic
        $userId = session()->get('userID');

        // ✅ FIXED: Use Query Builder correctly
        $db = \Config\Database::connect();
        $db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->update([
                'is_read' => 1
            ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'All notifications marked as read',
            'unread_count' => 0
        ]);
    }

    /**
     * ✅ View All Notifications Page
     */
    public function all()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $session = session();
        // ✅ Use 'userID' to match the enrollment logic
        $userId = $session->get('userID');

        // ✅ send session user data to header view
        $data['user'] = [
            'userID' => $session->get('userID'),
            'name'   => $session->get('name'),
            'email'  => $session->get('email'),
            'role'   => $session->get('role')
        ];

        $data['notifications'] = $this->notificationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('notifications/all', $data);
    }
}