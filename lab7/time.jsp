<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Session Control</title>
<link rel="stylesheet" href="${pageContext.request.contextPath}/style.css"></head>
<body>
<main>
<div class="card">
    <h2># 6 # информация о состоянии сессии</h2>
    <%-- messages — список строк, выводим без разделителей-скобок --%>
    <% for (Object m : (java.util.List<?>) request.getAttribute("messages")) { out.print(m); } %>
    <br/>
    <a href="timeoutservlet">Back to Servlet</a> · <a href="home.jsp">home.jsp</a>
</div>
</main>
</body>
</html>
