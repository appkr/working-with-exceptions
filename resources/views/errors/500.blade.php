<!DOCTYPE html>
<html>
<head>
  <title>Something went wrong.</title>
<body>

<div class="content">
  <div class="title">Something went wrong.</div>
  @unless(empty($sentryID))
  <!-- Sentry JS SDK 2.1.+ required -->
    <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

    <script>
      Raven.showReportDialog({
        eventId: '{{ $sentryID }}',

        // use the public DSN (dont include your secret!)
        dsn: 'https://cbcd70efec6a4f1c8e5852f8ba7346fe@sentry.io/131441'
      });
    </script>
  @endunless
</div>

</body>
</html>
