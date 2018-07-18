<?php
/*
HTTP:  Hyper Text Transfer Protocol  超文本传输协议

HTTP是一个属于应用层的面向对象的协议，由于其简捷、快速的方式，适用于分布式超媒体信息系统。它于1990年提出，经过几年的使用与发展，得到不断地完善和扩展。


特点:
	支持客户/服务器模式。
	简单快速：客户向服务器请求服务时，只需传送请求方法和路径。请求方法常用的有GET、HEAD、POST。每种方法规定了客户与服务器联系的类型不同。由于HTTP协议简单，使得HTTP服务器的程序规模小，因而通信速度很快。
	灵活：HTTP允许传输任意类型的数据对象。正在传输的类型由 Content-Type 加以标记。
	无连接：限制每次连接只处理一个请求。服务器处理完客户的请求，并收到客户的应答后，即断开连接。采用这种方式可以节省传输时间。
	无状态：HTTP协议是无状态协议。无状态是指协议对于事务处理没有记忆能力。缺少状态意味着如果后续处理需要前面的信息，则它必须重传，这样可能导致每次连接传送的数据量增大。另一方面，在服务器不需要先前信息时它的应答就较快。



Request:  请求行、消息报头、请求正文
	常用的请求报头:
		Accept:客户端能够处理的媒体类型。如text/html, 表示客户端让服务器返回html类型的数据，如果没有，返回text类型的也可以。媒体类型的格式一般为：type/subType,        
               表示优先请求subType类型的数据，如果没有，返回type类型数据也可以。
               常见的媒体类型：
					文本文件：text/html, text/plain, text/css, application/xml
					图片文件：iamge/jpeg, image/gif, image/png;
					视频文件：video/mpeg
					应用程序使用的二进制文件：application/octet-stream, application/zip

				Accept字段可设置多个字段值，这样服务器依次进行匹配，并返回最先匹配到的媒体类型，当然，也可通过q参数来设置
					媒体类型的权重，权重越高，优先级越高。q的取值为[0, 1], 可取小数点后3位，默认为1.0。例如：
					Accept: text/html, application/xml; q=0.9, *反斜杠*

		Accept-Charset:请求报头域用于指定客户端接受的字符集；例如：Accept-Charset:iso-8859-1,gb2312.如果在请求消息中没有设置这个域，缺省是任何字符集都可以接受。
		Accept-Encoding:请求报头域类似于Accept，但是它是用于指定可接受的内容编码；例如：Accept-Encoding:gzip.deflate，如果请求消息中没有设置这个域服务器假定客户端对各种内容编码都可以接受。
				常用的内容编码：
					gzip: 由文件压缩程序gzip生成的编码格式；
					compress: 由Unix文件压缩程序compress生成的编码格式；
					deflate: 组合使用zlib和deflate压缩算法生成的编码格式；
					identity：默认的编码格式，不执行压缩。

		If-Match: If-Match的值与所请求资源的ETag值（实体标记，与资源相关联。资源变化，实体标记跟着变化）一致时，服务器才处理此请求。
		If-Modified-Since: 用于确认客户端拥有的本地资源的时效性。 如果客户端请求的资源在If-Modified-Since指定的时间后发生了改变，则服务器处理该请求。如：If-Modified-Since:Thu 09 Jul 2018 00:00:00, 表示如果客户端请求的资源在2018年1月9号0点之后发生了变化，则服务器处理改请求。通过该字段我们可解决以下问题：有一个包含大量数据的接口，且实时性较高，我们在刷新时就可使用改字段，从而避免多余的流量消耗。
		If-None-Match: If-Match的值与所请求资源的ETag值不一致时服务器才处理此请求。
		If-Range： If-Range的值（ETag值或时间）与所访问资源的ETag值或时间相一致时，服务器处理此请求，并返回Range字段中设置的指定范围的数据。如果不一致，则返回所有内容。If-Range其实算是If-Match的升级版，因为它
		的值不匹配时，依然能够返回数据，而If-Match不匹配时，请求不会被处理，需要数据时需再次进行请求。
		Referer：告知服务器请求是从哪个页面发起的。例如在百度首页中搜索某个关键字，结果页面的请求头部就会有这个字段，其值为https://www.baidu.com/。通过这个字段可统计广告的点击情况。
		Accept-Language:请求报头域类似于 Accept，但是它是用于指定一种自然语言；例如：Accept-Language:zh-cn，如果请求消息中没有设置这个报头域，服务器假定客户端对各种语言都可以接受。
		Authorization:请求报头域主要用于证明客户端有权查看某个资源。当浏览器访问一个页面时，如果收到服务器的响应代码为401（未授权），可以发送一个包含 Authorization 请求报头域的请求，要求服务器对其进行验证。
		Host:请求报头域主要用于指定被请求资源的 Internet 主机和端口号，它通常从 HTTP URL 中提取出来的。
		Connection:例如：Connection: keep-alive 当一个网页打开完成后，客户端和服务器之间用于传输HTTP数据的TCP连接不会关闭，如果客户端再次访问这个服务器上的网页，会继续使用这一条已经建立的连接。
		User-Agent：列出了你的操作系统的名称和版本，浏览器的名称和版本，User-Agent 请求报头域允许客户端将它的操作系统、浏览器和其它属性告诉服务器。不过，这个报头域不是必需的。
		Cookie：最重要的请求头之一, 将cookie的值发送给HTTP服务器。



Response: 状态行、响应头、响应体

	状态行:HTTP-Version Status-Code Reason-Phrase CRLF
		  HTTP-Version表示服务器HTTP协议的版本
		  Status-Code表示服务器发回的响应状态代码
		  Reason-Phrase表示状态代码的文本描述

		  状态代码有三位数字组成，第一个数字定义了响应的类别，且有五种可能取值：
			1xx：指示信息--表示请求已接收，继续处理
			2xx：成功--表示请求已被成功接收、理解、接受
			3xx：重定向--要完成请求必须进行更进一步的操作
			4xx：客户端错误--请求有语法错误或请求无法实现
			5xx：服务器端错误--服务器未能实现合法的请求

			常见状态代码、状态描述、说明：
				200 OK                    //客户端请求成功
				400 Bad Request           //客户端请求有语法错误，不能被服务器所理解
				401 Unauthorized          //请求未经授权，这个状态代码必须和WWW-Authenticate报头域一起使用 
				403 Forbidden             //服务器收到请求，但是拒绝提供服务
				404 Not Found             //请求资源不存在，eg：输入了错误的URL
				500 Internal Server Error //服务器发生不可预期的错误
				503 Server Unavailable    //服务器当前不能处理客户端的请求，一段时间后可能恢复正常

HTTP1.0 HTTP 1.1主要区别
	长连接:
		HTTP 1.0需要使用keep-alive参数来告知服务器端要建立一个长连接，而HTTP1.1默认支持长连接。
		HTTP是基于TCP/IP协议的，创建一个TCP连接是需要经过三次握手的,有一定的开销，如果每次通讯都要重新建立连接的话，对性能有影响。因此最好能维持一个长连接，可以用个长连接来发多个请求。
	节约带宽:
		HTTP 1.1支持只发送header信息(不带任何body信息)，如果服务器认为客户端有权限请求服务器，则返回100，否则返回401。客户端如果接受到100，才开始把请求body发送到服务器。这样当服务器返回401的时候，客户端就可以不用发送请求body了，节约了带宽。
	HOST域:
		现在可以web server例如tomat，设置虚拟站点是非常常见的，也即是说，web server上的多个虚拟站点可以共享同一个ip和端口。HTTP1.0是没有host域的，HTTP1.1才支持这个参数。


HTTP1.1 HTTP 2.0主要区别:
	传输数据格式:
		HTTP/2采用二进制格式而非文本格式
	多路复用:
		在HTTP/1.1协议中，浏览器客户端在同一时间针对同一域名的请求有一定数据限制。超过限制数目的请求会被阻塞。
		HTTP2.0使用了多路复用的技术，做到同一个连接并发处理多个请求，而且并发请求的数量比HTTP1.1大了好几个数量级。
		当然HTTP1.1也可以多建立几个TCP连接，来支持处理更多并发的请求，但是创建TCP连接本身也是有开销的。
		TCP连接有一个预热和保护的过程，先检查数据是否传送成功，一旦成功过，则慢慢加大传输速度。因此对应瞬时并发的连接，服务器的响应就会变慢。所以最好能使用一个建立好的连接，并且这个连接可以支持瞬时并发的请求。

	数据压缩:
		HTTP1.1不支持header数据的压缩，HTTP2.0使用HPACK算法对header的数据进行压缩，这样数据体积小了，在网络上传输就会更快。
	服务器推送:
		意思是说，当我们对支持HTTP2.0的web server请求数据的时候，服务器会顺便把一些客户端需要的资源一起推送到客户端，免得客户端再次创建连接发送请求到服务器端获取。这种方式非常合适加载静态资源。
		服务器端推送的这些资源其实存在客户端的某处地方，客户端直接从本地加载这些资源就可以了，不用走网络，速度自然是快很多的。
*/








