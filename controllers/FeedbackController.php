<?php

include __DIR__ . "/../models/Feedback.php";

class FeedbackController {
	public static function addFeedback() {
        global $router;
        $data = $router->getPostRouteData();
        if ($data != null) {
            $feedback_model = new Feedback($data["name"], $data["phone"], $data["question"]);
            $feedback = QueryController::addFeedbackQuery(
                $feedback_model->name,
                $feedback_model->phone,
                $feedback_model->question
            );
            echo $feedback;
        } else {
            echo json_encode(array("response" => "Данные не дошли или неверные имена полей"));
        }
    }

    public static function getFeedbacks() {
        $feedbacks = QueryController::getFeedbacksQuery();
        echo $feedbacks;
    }
}