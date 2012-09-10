#include <stdio.h>

int main(void)
{
	char str1[60] = "To be or not to be";
	char str2[] = ", that is the question";
	
	int count1 = 0;
	int count2 = 0;
	
	while (str1[count1]) {
		count1 ++;	
	}

	while (str2[count2]) {
		count2 ++;	
	}

	if (sizeof(str1) < count1 + count2 + 1) {
		printf("\nYou can't put a quart into a pint pot.");	
	} else {
		count2 = 0;
		while (str2[count2]) {
			str1[count1++] = str2[count2++];	
		}	
		str1[count1] = '\0';
		printf("\n%s\n", str1);
	}

	return 0;
}
