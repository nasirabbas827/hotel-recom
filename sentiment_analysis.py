import sys
from textblob import TextBlob

# Get the feedback text from the command line argument
feedback_text = sys.argv[1]

# Perform sentiment analysis using TextBlob
analysis = TextBlob(feedback_text)

# Get the sentiment score (-1 to 1) and sentiment label
sentiment_score = analysis.sentiment.polarity
if sentiment_score > 0:
    sentiment_label = "Positive"
elif sentiment_score < 0:
    sentiment_label = "Negative"
else:
    sentiment_label = "Neutral"

# Print the sentiment score and label as a comma-separated string
print(f"{sentiment_score:.2f},{sentiment_label}")
