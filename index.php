<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calculate StressLevel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>

<div class="wrapper">
    <div class="title">Calculate StressLevel Chatbot</div>
    <div class="form">
        <div class="chat-box" id="chatBox">
            <!-- Chat messages will be displayed here -->
        </div>
        <div class="answer-options" id="answerOptions">
            <!-- Answer options will be displayed here -->
        </div>
        <div class="typing-field hidden">
            <div class="input-data">
                <input id="user-answer" type="text" placeholder="Type your answer here..." required>
            </div>
            <div>
                <button id="send-btn">Send</button>
            </div>
        </div>
        <div class="final-message hidden" id="finalMessage"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var answers = [
            ["Never", "Rarely", "Occasionally", "Often", "Always"],
            ["Never", "Rarely", "Sometimes", "Often", "Always"],
            ["Very well", "Fairly well", "Sometimes poorly", "Often poorly", "Very poorly"],
            ["Never", "Rarely", "Sometimes", "Often", "Always"],
            ["Complete control", "A lot of control", "Moderate control", "Little control", "No control"]
        ];
        var questions = [
            "Hi there! What is your name?", // Moved to the beginning
            "Would you like to answer some stress level calculation questions?",
            "How often do you feel overwhelmed by your daily responsibilities?",
            "How frequently do you experience physical symptoms of stress such as headaches or muscle tension?",
            "How well do you sleep at night?",
            "How often do you find it difficult to concentrate due to stress?",
            "How much control do you feel you have over the events in your life?"
        ];

        var currentQuestionIndex = 0;
        var stressLevel = 0;
        var userName = "";

        function displayMessage(message, sender) {
            var chatMessage = '<div class="message ' + sender + '">' + message + '</div>';
            $("#chatBox").append(chatMessage);
            // Scroll the chat box to the bottom
            $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
        }

        function displayQuestion() {
            var question = questions[currentQuestionIndex];
            displayMessage(question, 'bot');

            if (currentQuestionIndex === 0) {
                $(".typing-field").removeClass('hidden');
            } else if (currentQuestionIndex === 1) {
                var optionsContainer = '<div class="answer-options">';
                optionsContainer += '<button class="answer-button" data-answer="Yes">Yes</button>';
                optionsContainer += '<button class="answer-button" data-answer="No">No</button>';
                optionsContainer += '</div>';
                $("#answerOptions").html(optionsContainer);
            } else {
                var answerOptions = answers[currentQuestionIndex - 2];
                var optionsContainer = '<div class="answer-options">';
                answerOptions.forEach(function (option, optionIndex) {
                    optionsContainer += '<button class="answer-button" data-answer="' + option + '">' + option + '</button>';
                });
                optionsContainer += '</div>';
                $("#answerOptions").html(optionsContainer);
            }
        }

        function calculateStressLevel(answer) {
            if (currentQuestionIndex >= 2 && currentQuestionIndex <= questions.length) {
                var answerIndex = answers[currentQuestionIndex - 2].indexOf(answer);
                if (answerIndex !== -1) {
                    stressLevel += answerIndex + 1;
                }
            } else if (currentQuestionIndex === questions.length) {
                userName = answer;
            }
        }

        function displayStressLevel() {
            var totalStressQuestions = questions.length - 2;
            var percentageStress = (stressLevel / (totalStressQuestions * 5)) * 100;
            displayMessage("Your stress level is: " + percentageStress.toFixed(2) + "%", 'bot');
            $("#finalMessage").text("Thank you, " + userName + ", for completing the survey!");

            $(".typing-field").addClass('hidden');
            $(".input-data input, #send-btn").prop("disabled", true);
        }

        function sendMessage() {
            var userAnswer = $("#user-answer").val().trim();
            if (userAnswer !== "") {
                displayMessage(userAnswer, 'user');
                calculateStressLevel(userAnswer);
                currentQuestionIndex++;

                if (currentQuestionIndex < questions.length) {
                    displayQuestion();
                } else {
                    $(".answer-options, #user-answer, #send-btn").addClass('hidden');
                    displayStressLevel();
                    $("#user-answer").prop("disabled", true);
                    setTimeout(function () {
                        location.reload(); // Refresh the page after a short delay
                    }, 8000);
                }

                $("#user-answer").val("");
            }
        }

        $("#answerOptions").on("click", ".answer-button", function () {
            var selectedAnswer = $(this).data("answer");
            if (currentQuestionIndex === 1) {
                if (selectedAnswer.toLowerCase() === "yes") {
                    currentQuestionIndex++;
                    displayMessage(selectedAnswer, 'user');
                    displayQuestion();
                } else if (selectedAnswer.toLowerCase() === "no") {
                    displayMessage(selectedAnswer, 'user');
                    displayMessage("Thank you for your time!", 'bot');
                    $(".answer-options, .typing-field, #send-btn").addClass('hidden');
                    $(".input-data input").prop("disabled", true).addClass('hidden');
                    setTimeout(function () {
                        location.reload(); // Refresh the page after a short delay
                    }, 8000);
                }
            } else {
                $("#user-answer").val(selectedAnswer);
                $("#send-btn").prop("disabled", false);
                $(".answer-options, #user-answer, #send-btn").removeClass('hidden');
            }
        });

        $("#send-btn").on("click", function () {
            sendMessage();
        });

        displayQuestion();
    });
</script>
</body>
</html>
